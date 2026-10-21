<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = "Donate blood in Palakkad";
$pageDescription = "Blood bank availability in Palakkad district, Kerala. Find blood donors, blood banks, and ambulance services. Donate blood and save lives in Palakkad.";
$pageKeywords = "blood bank Palakkad, donate blood Palakkad, blood donation, blood availability, Palakkad blood bank, ambulance Palakkad";

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Donate Blood', 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4">Blood Bank - Availability</h1>
                
                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>
                
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-heart-pulse-fill"></i> <strong>Disclaimer:</strong> We can't guarantee the blood will be provided... We are finding closest matching eligible donor(s) and request them to donate blood.
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h3 class="mb-0"><i class="bi bi-droplet-fill"></i> Blood Availability</h3>
                    </div>
                    <div class="card-body">
                        <p class="lead">If you are in need of blood - please send a mail to <a href="mailto:support@palakkadtourism.org">support@palakkadtourism.org</a> with subject line: <strong>&lt;Place&gt; &lt;blood-group&gt; &lt;units&gt;</strong></p>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover mt-4">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Group</th>
                                        <th>AB+</th>
                                        <th>AB-</th>
                                        <th>A+</th>
                                        <th>A-</th>
                                        <th>B+</th>
                                        <th>B-</th>
                                        <th>O+</th>
                                        <th>O-</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>No. Peoples</strong></td>
                                        <td>22</td>
                                        <td>5</td>
                                        <td>46</td>
                                        <td>19</td>
                                        <td>56</td>
                                        <td>4</td>
                                        <td>63</td>
                                        <td>14</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="bi bi-arrow-left-right"></i> Blood Compatibility</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Recipient blood type</th>
                                        <th>Donor must be</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>AB+</strong></td>
                                        <td>Any blood type</td>
                                    </tr>
                                    <tr>
                                        <td><strong>AB-</strong></td>
                                        <td>O-, A-, B-, AB-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>A+</strong></td>
                                        <td>O-, O+, A-, A+</td>
                                    </tr>
                                    <tr>
                                        <td><strong>A-</strong></td>
                                        <td>O-, A-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>B+</strong></td>
                                        <td>O-, O+, B-, B+</td>
                                    </tr>
                                    <tr>
                                        <td><strong>B-</strong></td>
                                        <td>O-, B-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>O+</strong></td>
                                        <td>O-, O+</td>
                                    </tr>
                                    <tr>
                                        <td><strong>O-</strong></td>
                                        <td>O-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0"><i class="bi bi-building"></i> Blood Banks in Palakkad</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-hospital text-primary"></i> <strong>Palakkad District Hospital</strong>, Court Road, Palakkad - 678001</li>
                            <li class="mb-2"><i class="bi bi-hospital text-primary"></i> <strong>Appolo Lab And Blood Bank Services</strong>, Palayapet, Palakkad - 678001</li>
                            <li class="mb-2"><i class="bi bi-hospital text-primary"></i> <strong>The Palghat Diagnostic Centre</strong>, 210-A, T.B Road, Palakkad - 678014</li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0"><i class="bi bi-telephone-fill"></i> Ambulance Services</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>District Hospital Palakkad:</strong> <a href="tel:04912534524">0491-2534524</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Taluk Hospital Alathur:</strong> <a href="tel:04922222322">04922-222322</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Taluk Hospital Chittur:</strong> <a href="tel:04923222385">04923-222385</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Taluk Hospital Mannarkkad:</strong> <a href="tel:04924222380">04924-222380</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Taluk Hospital Ottappalam:</strong> <a href="tel:04662244480">0466-2244480</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Taluk Hospital Pattambi:</strong> <a href="tel:04662213769">0466-2213769</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Taluk Hospital Kollengode:</strong> <a href="tel:04923264352">04923-264352</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Alma Hospital Mannarkkad:</strong> <a href="tel:04924223036">04924-223036</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Assumption Hospital Kanhirapuzha:</strong> <a href="tel:04924238226">04924-238226</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Aswini Hospital Ottappalam:</strong> <a href="tel:04662244297">0466-2244297</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Co-operative Hospital Nemmara:</strong> <a href="tel:04923243216">04923-243216</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Crescent Hospital Alathur:</strong> <a href="tel:04922224411">04922-224411</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Karuna Hospital Palakkad:</strong> <a href="tel:04912538320">0491-2538320</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Lakshmi Nursing Home Palakkad:</strong> <a href="tel:04912529999">0491-2529999</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Muthalamada Sneham Alathur:</strong> <a href="tel:04922222567">04922-222567</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Nila Hospital Pattambi:</strong> <a href="tel:04662212565">0466-2212565</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Palakkad Medical & Research Centre Palakkad:</strong> <a href="tel:04912532522">0491-2532522</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Palghat Poly Clinic Palakkad:</strong> <a href="tel:04912534192">0491-2534192</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Railway Hospital Olavakode:</strong> <a href="tel:04912555333">0491-2555333</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Sandhyaram Hospital Kadambazhipuram:</strong> <a href="tel:04662267327">0466-2267327</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Semalk Hospital Ottappalam:</strong> <a href="tel:04662246438">0466-2246438</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>St. Joseph Hospital Mundur:</strong> <a href="tel:04912832323">0491-2832323</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Thankam Hospital Palakkad:</strong> <a href="tel:04912515719">0491-2515719</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Vadakkanchery Community Health Centre Vadakkanchery:</strong> <a href="tel:04922255005">04922-255005</a></li>
                                    <li class="mb-2"><i class="bi bi-telephone text-danger"></i> <strong>Valluvanad Hospital Ottappalam:</strong> <a href="tel:04662244423">0466-2244423</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h3 class="mb-0"><i class="bi bi-info-circle-fill"></i> RBC Compatibility Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h5><strong>Blood group A</strong></h5>
                            <p>Individuals have red blood cells with the A antigen on their surface, and blood serum containing antibodies against the B antigen. Therefore, a group A person can only receive blood from people in groups A or O (with A being preferable).</p>
                        </div>
                        
                        <div class="mb-3">
                            <h5><strong>Blood group B</strong></h5>
                            <p>Individuals have red blood cells with the B antigen on their surface, and blood serum containing antibodies against the A antigen. Therefore, a group B person can only receive blood from people in groups B or O (preferably B).</p>
                        </div>
                        
                        <div class="mb-3">
                            <h5><strong>Blood group AB</strong></h5>
                            <p>Individuals have red blood cells with both A and B surface antigens, and their blood serum does not contain antibodies against either A or B antigen. Therefore, a person with type AB blood can receive blood from any group (preferably AB) but cannot donate blood except to another AB.</p>
                        </div>
                        
                        <div class="mb-3">
                            <h5><strong>Blood group O</strong></h5>
                            <p>Individuals have red blood cells with neither A or B surface antigen, but their blood serum contains antibodies against both A and B antigens. Therefore, a group O person can only receive blood from group O.</p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <strong>Important:</strong> For emergency blood requirements, please contact the hospitals directly or send an email to <a href="mailto:support@palakkadtourism.org">support@palakkadtourism.org</a> with the subject line format: <strong>&lt;Place&gt; &lt;blood-group&gt; &lt;units&gt;</strong>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>




