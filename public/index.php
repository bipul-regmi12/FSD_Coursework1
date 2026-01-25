<?php
/**
 * Front Controller Router for Kiyome Pet Adoption Platform
 * 
 * This router implements the Front Controller Pattern to provide clean, SEO-friendly URLs
 * without exposing folder names or .php file extensions. All requests are rewritten to
 * this file via .htaccess, and this router maps clean URLs to the appropriate view files.
 * 
 * Routing Strategy:
 * 1. Parse and normalize the request URI
 * 2. Match against static routes (exact path matches)
 * 3. Match against dynamic routes (patterns with parameters like {id})
 * 4. Extract parameters from dynamic routes and inject them into $_GET
 * 5. Include the appropriate view file
 * 6. Return 404 for unmatched routes
 */

// #region agent log
require_once __DIR__ . '/../includes/functions.php';
debug_ndjson_log(
    'H1',
    'public/index.php:entry',
    'Router entry',
    [
        'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? null,
        'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? null,
        'PHP_SELF' => $_SERVER['PHP_SELF'] ?? null,
        'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? null,
    ],
    'pre-fix'
);
// #endregion

/**
 * Step 1: Parse and normalize the request URI
 * 
 * Extract the path from the request URI, handling subdirectories and trailing slashes.
 * This ensures consistent routing regardless of how the URL is accessed.
 */
$requestUri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'] ?? '/';

// Adjust for subdirectory if the application is not in the document root
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$scriptDir = str_replace('\\', '/', $scriptDir); // Normalize slashes

if ($scriptDir !== '/') {
    if (strpos($path, $scriptDir) === 0) {
        $path = substr($path, strlen($scriptDir));
    }
}

// Normalize the path: ensure it starts with / and remove trailing slashes (except root)
if (empty($path) || $path === '') {
    $path = '/';
}
if ($path !== '/' && substr($path, -1) === '/') {
    $path = rtrim($path, '/');
}

/**
 * Step 2: Define route mappings
 * 
 * Static routes: Exact path matches that map directly to view files
 * Dynamic routes: Patterns with placeholders (e.g., {id}) that capture parameters
 * 
 * Route format: 'pattern' => ['file' => 'path/to/file.php', 'params' => ['param_name' => 'segment_index']]
 * 
 * For dynamic routes, the pattern uses {param_name} to indicate where parameters are captured.
 * The segment index tells us which path segment (0-indexed) contains the parameter value.
 */
$staticRoutes = [
    '/' => 'home.php',
    '/login' => 'auth/login.php',
    '/register' => 'auth/register.php',
    '/logout' => 'auth/logout.php',
    '/pets' => 'pets/browse.php',
    '/pets/add' => 'pets/add.php',
    '/dashboard/adopter' => 'dashboard/adopter.php',
    '/dashboard/shelter' => 'dashboard/shelter.php',
    '/applications/submit' => 'applications/submit.php',
    '/applications/status' => 'applications/status.php',
];

/**
 * Dynamic routes with parameter extraction
 * 
 * Pattern format: '/path/{param_name}/more/path'
 * The router will:
 * 1. Split the pattern and request path into segments
 * 2. Match segment by segment, capturing values where {param_name} appears
 * 3. Extract the captured values and inject them into $_GET
 * 
 * Example: Pattern '/pets/{id}' matches '/pets/12' and sets $_GET['id'] = 12
 */
$dynamicRoutes = [
    // /pets/{id} -> pets/view.php with $_GET['id'] = {id}
    '/pets/{id}' => 'pets/view.php',
    // /pets/edit/{id} -> pets/edit.php with $_GET['id'] = {id}
    '/pets/edit/{id}' => 'pets/edit.php',
    // /pets/delete/{id} -> pets/delete.php with $_GET['id'] = {id}
    '/pets/delete/{id}' => 'pets/delete.php',
];

/**
 * Step 3: Try to match static routes first (faster, exact matches)
 */
if (isset($staticRoutes[$path])) {
    // #region agent log
    debug_ndjson_log(
        'H2',
        'public/index.php:route-match-static',
        'Static route matched',
        [
            'normalized_path' => $path,
            'target' => $staticRoutes[$path],
        ],
        'pre-fix'
    );
    // #endregion
    
    require __DIR__ . '/' . $staticRoutes[$path];
    exit;
}

/**
 * Step 4: Try to match dynamic routes
 * 
 * For each dynamic route pattern:
 * 1. Split both pattern and request path into segments
 * 2. Compare segments - they must match except where {param} appears
 * 3. If match found, extract parameter values and inject into $_GET
 * 4. Include the target file
 */
$pathSegments = explode('/', trim($path, '/'));
$matched = false;

foreach ($dynamicRoutes as $pattern => $targetFile) {
    $patternSegments = explode('/', trim($pattern, '/'));
    
    // Must have same number of segments
    if (count($pathSegments) !== count($patternSegments)) {
        continue;
    }
    
    // Check if segments match (ignoring {param} placeholders)
    $matches = true;
    $extractedParams = [];
    
    for ($i = 0; $i < count($patternSegments); $i++) {
        $patternSeg = $patternSegments[$i];
        $pathSeg = $pathSegments[$i];
        
        // If this is a parameter placeholder (e.g., {id})
        if (preg_match('/^{(.+)}$/', $patternSeg, $paramMatch)) {
            $paramName = $paramMatch[1];
            // Validate numeric ID parameters to prevent injection
            if ($paramName === 'id') {
                if (!ctype_digit($pathSeg)) {
                    $matches = false;
                    break;
                }
                $extractedParams[$paramName] = (int)$pathSeg; // Cast to int for safety
            } else {
                $extractedParams[$paramName] = $pathSeg;
            }
        } 
        // Otherwise, segments must match exactly
        elseif ($patternSeg !== $pathSeg) {
            $matches = false;
            break;
        }
    }
    
    // If we found a match, inject parameters and include the file
    if ($matches) {
        // Inject extracted parameters into $_GET so existing code continues to work
        // Preserve existing $_GET parameters (e.g., query strings)
        foreach ($extractedParams as $paramName => $paramValue) {
            $_GET[$paramName] = $paramValue;
        }
        
        // #region agent log
        debug_ndjson_log(
            'H2',
            'public/index.php:route-match-dynamic',
            'Dynamic route matched',
            [
                'normalized_path' => $path,
                'pattern' => $pattern,
                'target' => $targetFile,
                'extracted_params' => $extractedParams,
            ],
            'pre-fix'
        );
        // #endregion
        
        require __DIR__ . '/' . $targetFile;
        $matched = true;
        break;
    }
}

/**
 * Step 5: Handle 404 - Route not found
 * 
 * If no static or dynamic route matched, return a 404 error page.
 * This ensures invalid URLs don't expose internal structure.
 */
if (!$matched) {
    // #region agent log
    debug_ndjson_log(
        'H3',
        'public/index.php:route-miss',
        'Route not found',
        [
            'normalized_path' => $path,
            'scriptDir' => $scriptDir ?? null,
        ],
        'pre-fix'
    );
    // #endregion
    
    http_response_code(404);
    require_once __DIR__ . '/../includes/header.php';
    echo '<div class="container" style="padding: 4rem; text-align: center;">';
    echo '<h1 class="gradient-text">404</h1>';
    echo '<p>Page not found</p>';
    echo '<p>The page you are looking for does not exist.</p>';
    echo '<a href="/" class="btn btn-primary" style="margin-top: 2rem;">Return to Home</a>';
    echo '</div>';
    require_once __DIR__ . '/../includes/footer.php';
}
?>
