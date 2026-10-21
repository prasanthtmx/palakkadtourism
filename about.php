<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = "About Palakkad";
$pageDescription = "Learn about Palakkad (Palghat), Kerala - its history, culture, heritage, and significance. Discover why Palakkad is a unique district in Kerala with rich historical importance.";
$pageKeywords = "About Palakkad, Palakkad history, Palghat Kerala, Palakkad culture, Palakkad district information";

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'About Palakkad', 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4">About Palakkad</h1>
                
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-info-circle"></i> <strong>Note:</strong> Please refrain from throwing plastic bottles or covers on paddy fields or roadside. Let's keep Palakkad clean and preserve its natural charm.
                </div>
                
                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <h2 class="mt-5 mb-3">Palakkad, Palghat (പാലക്കാട്)</h2>
                <p class="lead">Palakkad district possesses distinctive characteristics that highlight its historical significance.</p>
                
                <p>The fort of Hyder Ali narrates the tale of Mysore invasions and the arrival of the British in this region of the country. In 1886, the inception of Victoria College marked the commencement of higher education in Malabar. Notably, the Jain temple near Chunnamputhara stands as a testament to the benevolence of the king of Palakkad, who offered refuge to those fleeing religious persecutions.</p>

                <h3 class="mt-4 mb-3">Ancient History</h3>
                <p>The ancient history of Palakkad is veiled in mystery. According to William Logan, the author of the Malabar Manual, it is speculated that the Pallava dynasty of Kanchi might have conducted invasions in Malabar during the second or third century. It is believed that Palakada, possibly present-day Palakkad, served as one of their strongholds.</p>
                
                <p>Numerous ancient South Indian rulers invaded Malabar throughout its history. The Perumals held dominion over the region for many centuries. They governed alongside influential Utayavars who exercised authority over their respective territories. Following the reign of the Perumals, the land was divided among these chieftains. Notable rulers who emerged during this period were the Valluvakkonathiri (ruler of Valluvanad), the rulers of Vengunad (Kollengodu Rajas), and Sekhari Varma (Raja of Palakkad), all of whom played prominent roles in shaping the region.</p>

                <h3 class="mt-4 mb-3">Mysore Rule</h3>
                <p>In 1757, when the Zamorin of Kozhikode (known as Samoothiri in Malayalam, Samorim in Portuguese, Samorijn in Dutch, and Shamitihsi in Chinese) launched an invasion of Palakkad, the raja of Palakkad sought assistance from Hyder Ali of Mysore. With Hyder Ali's support, the Zamorin was compelled to retreat. Subsequently, Hyder Ali conquered all the territories in Palakkad that were formerly under the Zamorin's control. As a result, the entire region governed by the Raja of Palakkad came under the rule of the Mysore rulers, Hyder Ali and his son Tipu Sultan.</p>
                
                <p>The conflict between Tipu Sultan and the East India Company concluded with the signing of the 1872 treaty, which resulted in the transfer of all of Tipu Sultan's possessions in Malabar to the British. Over time, these territories became part of the Malabar district within the Madras Presidency.</p>

                <h3 class="mt-4 mb-3">Formation of Palakkad District</h3>
                <p>Palakkad district was officially formed on November 1, 1956, as part of the reorganization of states in India. This reorganization came into effect with the States Reorganization Act of 1956, which aimed to create linguistic states based on the dominant language spoken in each region. As a result, the former Madras Presidency was divided, and the state of Kerala was established, encompassing several regions, including Palakkad. Thus, Palakkad emerged as a distinct administrative district within the newly formed state of Kerala. Since then, it has flourished as an integral part of Kerala, with its own unique cultural and historical significance.</p>

                <h3 class="mt-4 mb-3">Cultural Heritage</h3>
                <p>Palakkad is known for its rich cultural heritage, diverse religious communities, and beautiful natural landscapes. The district is home to numerous temples, churches, and mosques, each with its own historical significance. The region celebrates various festivals throughout the year, showcasing the unity in diversity that characterizes Kerala.</p>

                <h3 class="mt-4 mb-3">Tourism</h3>
                <p>Palakkad offers a wide range of tourist attractions, from historical forts and ancient temples to beautiful hill stations and wildlife sanctuaries. The district's natural beauty, combined with its rich history and culture, makes it a popular destination for tourists from around the world.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

