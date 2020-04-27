<?php
	session_start();
	include ("db_init.php");
	include ("init.php");
	include ("functions.php");
	include ("init_process.php");
?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title>RSM - Rádió Státusz Monitor</title>
<!--
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-grid.min.css"/>
-->
<!-- BOOTSTRAP 4.4 -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<!-- EOF - BOTTSTRAP 4.4 -->
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="style.css">
<!-- <link href="./favicon.ico" rel="icon" type="image/x-icon" /> -->
<link rel="stylesheet" type="text/css" href="neonclock.css">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<script>
</script>
<script src="/rsm_core-1.2.0.js"></script>
</head> 
<?php
?>

<body>
<div class="container-fluid">
	<div class="row">
        <div class="col-9">
            <h3>98.4Mega On-Air: <span id="onair-mega">Kapcsolódás...</span></h3>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-3">
                        <div id = "dsp_m_card" class="card text-white bg-secondary">
                            <div class="card-body">
                                <h5 class="card-title" id="dsp_m">Kapcsolódás...</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div id = "dsp_p_card" class="card text-white bg-secondary">
                            <div class="card-body">
                                <h5 class="card-title" id="dsp_p">Kapcsolódás...</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-3">
                        <div id = "barix_984mega_card" class="card text-white bg-secondary">
                            <div class="card-body">
                                <h5 class="card-title" id="barix_984mega">Kapcsolódás...</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div id = "barix_oroshaza_card" class="card text-white bg-secondary">
                            <div class="card-body">
                                <h5 class="card-title" id="barix_oroshaza">Kapcsolódás...</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div id = "barix_gyula_card" class="card text-white bg-secondary">
                            <div class="card-body">
                                <h5 class="card-title" id="barix_gyula">Kapcsolódás...</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3" style="background-color: #202020">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-5">
                        <h1>RSM</h1>
                    </div>
                    <div class="col-7">
                      <div class='neclk_container'></div>
                      <span class="neclk_time"></span>
                    </div>
                </div>
            </div>
			<div class="container-fluid">
            	<h3>Gépház események</h3>
            </div>
        </div>
    	
	</div>
</div>
 
</body>
</html>
