<?php
date_default_timezone_set('America/Denver');
require_once "app/database/connection.php";
require_once "path.php";
session_start();

$files = glob("app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}
logoutUser($conn);
if(isLoggedIn() == false) {
    header('location:' . BASE_URL . '/login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/main.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <title>Job Management System</title>

    <style>
        .application-details {
            /* background-color: rgb(240, 240, 240); */
            max-width: 80%;
            /* border-radius: 15px; */
            padding: 20px;
            margin: 20px auto;
            /* box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); */
        }

        .application-details h2 {
            /* color: #333; */
            /* margin-bottom: 10px; */
        }

        .detail-label {
            font-weight: bold;
        }

        .detail-value {
            margin-bottom: 10px;
        }
        .card-container {
            display: flex;
            justify-content: space-between; /* Distribute space between items */
            /* flex-wrap: wrap; Enable wrapping */
        }
        
        .card {
            width: calc(33.33% - 20px); /* 33.33% for desktop layout */
            margin-bottom: 20px; /* Add space below cards */
        }
        
        @media (max-width: 992px) {
            .card-container {
                flex-wrap: wrap;
            }
            .card {
                width: 100%; /* 50% width on tablets */
            }
        }


    </style>
</head>
<body>

<?php include(ROOT_PATH . "/app/database/includes/header.php"); ?>

<div class="container-fluid main">
    <div class="application-details">
        <?php
        $id = $_GET['viewid'];
        $sql = "SELECT * FROM applications WHERE app_id=$id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $num_rows = mysqli_num_rows($result);
            while ($row = mysqli_fetch_assoc($result)) {
                $job_title = $row['job_title'];
                $company = $row['company'];
                $location = $row['location'];
                $pay = $row['pay'];
                $bonus_pay = $row['bonus_pay'];
                $status = $row['status'];
                $job_type = $row['job_type'];
                $app_link = $row['app_link'];
                $notes = $row['notes'];
                $watchlist = $row['watchlist'];
                $interview_set = $row['interview_set'];
                $created_at = $row['created_at'];
                $updated_at = $row['updated_at'];
                ?>

                <h2>
                    <?php echo $job_title; ?>
                    <span class="ps-3" style="font-size: 14px;">
                        <?php if($status == 'Applied'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-primary"></i> &nbsp; <?php echo $status; ?></span>
                        <?php } else if($status == 'Interviewed'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-info"></i> &nbsp; <?php echo $status; ?></span>
                        <?php } else if($status == 'Offered'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-success"></i> &nbsp; <?php echo $status; ?></span>
                        <?php } else if($status == 'Rejected'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-danger"></i> &nbsp; <?php echo $status; ?></span>
                        <?php } else if($status == 'Interested'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-secondary"></i> &nbsp; <?php echo $status; ?></span>
                        <?php } ?>
                    </span>
                    <span class="float-end">
                        <a class="badge text-bg-success text-decoration-none" style="font-size: 14px;" href="update-app.php?updateid=<?php echo $id; ?>">Edit</a>
                        <a class="badge text-bg-danger text-decoration-none" style="font-size: 14px;" href="open-app.php?deleteid=<?php echo $id; ?>">Delete</a>
                    </span>
                </h2>

                <?php 
                    $updated_time = strtotime($updated_at);
                    $updated_at_formatted = date('M j, Y', $updated_time);
                    $created_time = strtotime($created_at);
                    $created_at_formatted = date('M j, Y', $created_time);
                ?>
                <p class="text-muted" style="font-size: 12px;">
                    <span class="pe-3">
                        Last updated: <?php echo $updated_at_formatted; ?>
                    </span>
                    <span>
                        Applied: <?php echo $created_at_formatted; ?>
                    </span>
                    <span>
                        <?php if($interview_set == 1 && $watchlist == 0){ ?>
                            <span class="pe-3"></span><i class="bi bi-person-video"></i>
                        <?php } else if($interview_set == 0 && $watchlist == 1) { ?>
                            <span class="pe-3"></span><i class="bi bi-eye-fill"></i>
                        <?php } else if($interview_set == 1 && $watchlist == 1) { ?>
                            <span class="pe-3"></span><i class="bi bi-person-video"></i>&nbsp;&nbsp;<i class="bi bi-eye-fill"></i>
                        <?php } else { }
                        ?>
                    </span>
                </p>
                
                <h4><i class="bi bi-briefcase-fill"></i> Job details</h4>


                <div class="card-container">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Company</h5>
                            <p class="card-text"><?php echo $company; ?></p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Location</h5>
                            <p class="card-text"><?php echo $location; ?></p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Job Type</h5>
                            <p class="card-text"><?php echo $job_type; ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-container">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Base Pay</h5>
                            <p class="card-text">
                                <?php if(!empty($pay)) { ?>
                                    $<?php echo $pay; ?>
                                <?php } else { ?>
                                <span class="text-warning">No base pay found.</span>
                                <?php } ?>
                            </p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Bonus Pay</h5>
                            <p class="card-text">
                                <?php if(!empty($bonus_pay)) { ?>
                                    $<?php echo $bonus_pay; ?>
                                <?php } else { ?>
                                <span class="text-warning">No bonus pay found.</span>
                                <?php } ?>
                            </p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Job Listing</h5>
                            <p class="card-text"><a href="<?php echo $app_link; ?>" target="_blank" class="badge text-bg-secondary text-decoration-none" style="margin-top: -10px !important; padding: none !important;">Visit</a></p>
                        </div>
                    </div>
                </div>

                <h4><i class="bi bi-file-earmark-text-fill"></i> Notes</h4>
                
                <!-- __________ -->
                
                <div id="accordion">
                    <?php
                    // Check if $notes is not empty
                    if (!empty($notes)) {
                        // Match all <h5> tags and their content
                        preg_match_all('/<h5>(.*?)<\/h5>(.*?)(?=<h5>|$)/s', $notes, $matches, PREG_SET_ORDER);
                    
                        // Loop through each matched note
                        foreach ($matches as $index => $match) {
                            // Extract title and content
                            $title = $match[1];
                            $content = $match[2];
                        
                            // Display accordion item
                            ?>
                            <div class="accordion-item">
                                
                                <h5 class="accordion-header" id="heading<?= $index ?>">
                                    <button class="accordion-button collapsed" id="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
                                        <?= $title ?> <!-- Use text within <h5> tags as button/title -->
                                        <i id="chev" class="bi bi-chevron-down" style="position: absolute; top: 50%; transform: translateY(-50%); right: 10px;"></i>
                                    </button>
                                </h5>
                                <div id="collapse<?= $index ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index ?>" data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <?= $content ?> <!-- Output the content under the <h5> tag as accordion body -->
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        // Display message if $notes is empty
                        ?>
                        <div class="accordion-item">
                            <h5 class="accordion-header">
                                <span class="text-warning">No notes found.</span>
                            </h5>
                        </div>
                        <?php
                    }
                    ?>
                </div>





                <!-- ___________ -->
                

            <?php }
        } ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    var accordionButton = document.getElementById('accordion-button');
    if (accordionButton) {
        var chev_i = document.getElementById('chev');
        
        if (chev_i) {
            accordionButton.addEventListener('click', function() {
                
                var isCollapsed = accordionButton.classList.contains('collapsed');
                
                if (isCollapsed) {
                    chev_i.classList.remove('bi-chevron-up');
                    chev_i.classList.add('bi-chevron-down');
                } else {
                    chev_i.classList.remove('bi-chevron-down');
                    chev_i.classList.add('bi-chevron-up');
                }
            });
        } else {
            console.log('Chevron icon not found');
        }
    } else {
        console.log('Accordion button not found');
    }
});

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
