<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('shelter');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        redirect('/dashboard/shelter.php?error=Security session expired.');
    }

    $appId = $_POST['app_id'];
    $newStatus = $_POST['status']; // 'approved' or 'rejected'
    $shelterId = $_SESSION['user_id'];

    if (!in_array($newStatus, ['approved', 'rejected'])) {
        redirect('/dashboard/shelter.php?error=Invalid status.');
    }

    try {
        $pdo->beginTransaction();

        // 1. Verify this application belongs to a pet owned by this shelter (IDOR check)
        $stmt = $pdo->prepare("
            SELECT aa.id, aa.pet_id, aa.applicant_email, aa.applicant_name, p.name as pet_name, p.shelter_id 
            FROM adoption_applications aa
            JOIN pets p ON aa.pet_id = p.id
            WHERE aa.id = ? AND p.shelter_id = ?
        ");
        $stmt->execute([$appId, $shelterId]);
        $app = $stmt->fetch();

        if ($app) {
            // 2. Update application status
            $stmt = $pdo->prepare("UPDATE adoption_applications SET status = ? WHERE id = ?");
            $stmt->execute([$newStatus, $appId]);

            // 3. Update pet status accordingly
            if ($newStatus === 'approved') {
                $stmt = $pdo->prepare("UPDATE pets SET status = 'adopted' WHERE id = ?");
                $stmt->execute([$app['pet_id']]);

                // Reject other pending applications for this pet
                $stmt = $pdo->prepare("UPDATE adoption_applications SET status = 'rejected' WHERE pet_id = ? AND id != ? AND status = 'pending'");
                $stmt->execute([$app['pet_id'], $appId]);
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM adoption_applications WHERE pet_id = ? AND status = 'pending'");
                $stmt->execute([$app['pet_id']]);
                $remainingPending = $stmt->fetchColumn();

                if ($remainingPending == 0) {
                    $stmt = $pdo->prepare("SELECT status FROM pets WHERE id = ?");
                    $stmt->execute([$app['pet_id']]);
                    $petStatus = $stmt->fetchColumn();
                    if ($petStatus !== 'adopted') {
                        $stmt = $pdo->prepare("UPDATE pets SET status = 'available' WHERE id = ?");
                        $stmt->execute([$app['pet_id']]);
                    }
                }
            }

            $pdo->commit();

            // 4. Send Email Notification
            if ($app['applicant_email']) {
                $to = $app['applicant_email'];
                $subject = "Update on your adoption application for " . $app['pet_name'];
                $adopterName = $app['applicant_name'] ?: 'Applicant';

                if ($newStatus === 'approved') {
                    $body = "Dear $adopterName,\n\nGreat news! Your application to adopt " . $app['pet_name'] . " has been APPROVED!\n\nPlease contact the shelter to arrange the next steps.";
                } else {
                    $body = "Dear $adopterName,\n\nWe appreciate your interest in " . $app['pet_name'] . ", but we have decided to move forward with another applicant at this time.\n\nThank you for your understanding.";
                }

                $headers = "From: noreply@kiyome.com\r\n";
                @mail($to, $subject, $body, $headers);

                debug_ndjson_log('EMAIL_SENT', 'applications/status.php', "Email sent to $to", [
                    'to' => $to,
                    'subject' => $subject,
                    'status' => $newStatus
                ]);
            }

            redirect('/dashboard/shelter.php?msg=status_updated');
        } else {
            throw new Exception("Unauthorized access.");
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction())
            $pdo->rollBack();
        redirect('/dashboard/shelter.php?error=' . urlencode($e->getMessage()));
    }
} else {
    redirect('/dashboard/shelter.php');
}
?>