<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = "Rivers of Palakkad";
$pageDescription = "Discover the rivers of Palakkad district, Kerala. Learn about Bharathappuzha (Nila) and its tributaries - Gayathripuzha, Kannadipuzha, Kalpathypuzha, Thuthapuzha, and the Bhavani river.";
$pageKeywords = "Palakkad rivers, Bharathappuzha, Nila river, Gayathripuzha, Kannadipuzha, Kalpathypuzha, Thuthapuzha, Bhavani river, Palakkad water bodies, Kerala rivers";

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Geography', 'url' => '#'],
    ['title' => 'Rivers', 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4">Rivers of Palakkad</h1>
                
                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>
                
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-droplet"></i> <strong>Note:</strong> Please refrain from throwing plastic bottles or covers on paddy fields or roadside. Let's keep Palakkad's rivers clean and preserve their natural beauty.
                </div>

                <h2 class="mt-5 mb-3">Bharathappuzha</h2>
                <p>Bharathappuzha, with her tributaries, sprawls across the entire district. The river takes its origin from Anamalai hills and flows through the districts of Palakkad, Malappuram and Thrissur before emptying into the Arabian sea at Ponnani. Its four main tributaries are Gayatripuzha, Kannadipuzha, Kalpathypuzha and Thuthapuzha.</p>
                
                <p>The length of Bharathappuzha is 374.40 kms and its catchment area is 6186 sq.kms.</p>

                <h3 class="mt-4 mb-3">Gayathripuzha</h3>
                <p>This river originates from Anamalai hills and after traversing through Kollengode, Nanmara, Alathur, Wadakkancherry and pazhayannur, joins Bharathappuzha at Mayannur. This tributary has five main sub-tributaries. They are Managalam river, Ayalurpuzha, Vandazhipuzha, Meenkara puzhas and chulliyar.</p>

                <h3 class="mt-4 mb-3">Kannadipuzha</h3>
                <p>It is also known as Chitturpuzha or Amaravathi puzha. This river, which also starts from the Anamalai hills, flows through Thathamangalam and Chittur and joins the main river at Parli. Three main streams combine to form this river. They are Palar, Aliyar and Uppar.</p>

                <h3 class="mt-4 mb-3">Kalpathypuzha</h3>
                <p>This river starts from the place called Chenthamarakulam in the hills, north of Walayar. This is also known as Korayar. Kalpathypuzha is formed by four streams, viz., Korayar, Varattar, Walayar and Malapuzha.</p>

                <h3 class="mt-4 mb-3">Thuthapuzha</h3>
                <p>Thuthapuzha other wise known as Pilanthol river, starts from the Silent Valley hills and joins the main river about two kilometers off Pallippuram railway station. The important streams, which feed this tributary, are Kunthipuzha, Kanjirapuzha, Ambankadavu and Thuppanadipuzha.</p>

                <h2 class="mt-5 mb-3">Bhavani River</h2>
                <p>The Bhavani river originates from the Kunda mountains in the Nilgiris, makes a circuitous course through the Attappady valley and returns to the shadow of Nilgiri mountains. The catchments area of the Bhavani river within Kerala is 220 sq.miles yielding an annual run off of 27,000 million cubic feet of water. Of the rivers of Kerala, Bhavani river is one among the three which prefers Bay of Bengal to the Arabian sea.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>




