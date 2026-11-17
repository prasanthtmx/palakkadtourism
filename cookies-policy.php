<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = "Cookies Policy";
$pageDescription = "Learn how Palakkad Tourism uses cookies to improve your browsing experience, including analytics, advertising, and functional cookies.";

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Cookies Policy', 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4">Cookies Policy</h1>
                <p class="text-muted mb-4"><small>Last updated: November 2026</small></p>

                <p class="lead">This Cookies Policy explains how Palakkad Tourism (<a href="<?php echo htmlspecialchars(SITE_URL); ?>"><?php echo htmlspecialchars(SITE_URL); ?></a>) uses cookies and similar technologies when you visit our website.</p>

                <h2 class="mt-5 mb-3">What Are Cookies?</h2>
                <p>Cookies are small text files placed on your device (computer, tablet, or mobile) when you visit a website. They help the website remember your preferences, improve performance, and provide relevant content. Cookies do not contain personally identifiable information on their own.</p>

                <h2 class="mt-5 mb-3">How We Use Cookies</h2>
                <p>Palakkad Tourism uses cookies for the following purposes:</p>

                <h3 class="mt-4 mb-3">1. Strictly Necessary Cookies</h3>
                <p>These cookies are essential for the website to function properly. They enable core features such as security and accessibility. You cannot opt out of these cookies.</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Cookie</th>
                                <th>Purpose</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>cookie_consent</code> (cookie and local storage)</td>
                                <td>Stores your cookie consent preferences (Accept or Decline)</td>
                                <td>1 year</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3 class="mt-4 mb-3">2. Analytics Cookies</h3>
                <p>We use Google Analytics 4 to understand how visitors interact with our website. This helps us improve content and user experience. Analytics cookies are used unless you click <strong>Decline</strong>. The data collected is aggregated, and IP addresses are anonymised.</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Cookie</th>
                                <th>Provider</th>
                                <th>Purpose</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>_ga</code></td>
                                <td>Google Analytics</td>
                                <td>Distinguishes unique users</td>
                                <td>2 years</td>
                            </tr>
                            <tr>
                                <td><code>_ga_*</code></td>
                                <td>Google Analytics</td>
                                <td>Maintains session state</td>
                                <td>2 years</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3 class="mt-4 mb-3">3. Advertising Cookies</h3>
                <p>We use Google AdSense to display relevant advertisements. These cookies help serve ads and measure their effectiveness. Google may use these cookies to personalise ads based on your interests.</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Cookie</th>
                                <th>Provider</th>
                                <th>Purpose</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>__gads</code></td>
                                <td>Google AdSense</td>
                                <td>Ad frequency and relevance</td>
                                <td>13 months</td>
                            </tr>
                            <tr>
                                <td><code>__gpi</code></td>
                                <td>Google AdSense</td>
                                <td>Ad personalisation</td>
                                <td>13 months</td>
                            </tr>
                            <tr>
                                <td><code>NID</code></td>
                                <td>Google</td>
                                <td>Stores preferences for Google services</td>
                                <td>6 months</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3 class="mt-4 mb-3">4. Social Media &amp; Functional Cookies</h3>
                <p>When you interact with social media buttons (Facebook, X/Twitter) or use the chatbot feature, those third-party services may set their own cookies. We do not control these cookies.</p>

                <h2 class="mt-5 mb-3">Managing Your Cookie Preferences</h2>
                <p>You can manage or withdraw your consent at any time. Here are your options:</p>
                <ul>
                    <li><strong>Cookie banner:</strong> Use the Accept or Decline buttons when you first visit the site. Decline turns analytics and advertising cookies off.</li>
                    <li><strong>Browser settings:</strong> Most browsers allow you to block or delete cookies. See your browser's help pages for instructions.</li>
                    <li><strong>Google opt-out:</strong> You can opt out of Google Analytics tracking at <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer">tools.google.com/dlpage/gaoptout</a>.</li>
                    <li><strong>Ad personalisation:</strong> Manage Google ad settings at <a href="https://adssettings.google.com" target="_blank" rel="noopener noreferrer">adssettings.google.com</a>.</li>
                </ul>
                <p class="text-muted"><em>Note: Blocking some cookies may affect the functionality of this website.</em></p>

                <h2 class="mt-5 mb-3">Third-Party Cookies</h2>
                <p>Some pages on our site may include content from third-party services such as Google Maps, YouTube, or Facebook. These services may set their own cookies subject to their own privacy policies. We recommend reviewing their policies directly:</p>
                <ul>
                    <li><a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Google Privacy Policy</a></li>
                    <li><a href="https://www.facebook.com/privacy/policy/" target="_blank" rel="noopener noreferrer">Facebook Privacy Policy</a></li>
                </ul>

                <h2 class="mt-5 mb-3">Changes to This Policy</h2>
                <p>We may update this Cookies Policy from time to time. Any changes will be posted on this page with an updated date. We encourage you to review this page periodically.</p>

                <h2 class="mt-5 mb-3">Contact Us</h2>
                <p>If you have any questions about our use of cookies, please contact us at <a href="mailto:<?php echo htmlspecialchars(SITE_EMAIL); ?>"><?php echo htmlspecialchars(SITE_EMAIL); ?></a>.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
