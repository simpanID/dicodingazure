<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
$connectionString = "DefaultEndpointsProtocol=https;AccountName=mydicoding;AccountKey=5S4PMNOPjXXOzKmxPqHkLFGNISk7EnppRqE6BPw7BPq2Dj/D9XENCArKBQfW/fAlsGzjjSlM/mNQXO1S/teOgw==;EndpointSuffix=core.windows.net";
$containerName = "vision";
			
$blobClient = BlobRestProxy::createBlobService($connectionString);
if (isset($_POST['submit'])) 
{	
$fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);
$content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");	
$blobClient->createBlockBlob($containerName, $fileToUpload, $content);	
}
$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Computer Vision</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>
			
 <div class="jumbotron">
  <h1 class="display-4">Computer Vision</h1>
  <p class="lead">Analisis Image atau Gambar Menggunakan Computer Vision Azure.</p>
  <hr class="my-4">
  <p>Pilih gambar lalu lakukan upload untuk menganalisis menggunakan fitur vision.</p>
<div class="starter-template">
				<span class="border-top my-3"></span>
			</div>
		<div class="mt-4 mb-2">
			<form id="imagesupload" class="d-flex justify-content-lefr" action=""  method="post" enctype="multipart/form-data">
				<input type="file" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
				<input type="submit" name="submit" value="Upload">
			</form>

			</div>
	</div>
		<?php
				do {
					$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
					foreach ($result->getBlobs() as $blob)
					{
						?>
<div class="card mb-3" style="max-width: 540px;">
  <div class="row no-gutters">
    <div class="col-md-4">
      <img src="<?php echo $blob->getUrl() ?>" class="card-img" alt="...">
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title"><?php echo $blob->getName() ?></h5>
        <p class="card-text" ><?php echo $blob->getUrl() ?></p>
        <form action="vision.php" method="post">
									<input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">
									<input type="submit" name="submit" value="Analisis" class="btn btn-primary">
								</form>
      </div>
    </div>
  </div>
</div>


<?php
					}
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while($result->getContinuationToken());
				?>
</body>