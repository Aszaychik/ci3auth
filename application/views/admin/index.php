
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Admin Profile</h1>
										
										<div class="card mb-3" style="max-width: 540px;">
										<div class="row no-gutters">
											<?php 
												if(empty($user['image'])){
														$image = 'https://source.unsplash.com/random/128x128';
												}else{
														$image = base_url('assets/img/') . $user['image'];
												}
												?>
											<div class="col-md-4" style="background-image: url('<?= $image; ?>'); background-repeat: no-repeat; background-size: cover; background-position: center;">
											</div>
											<div class="col-md-8">
												<div class="card-body">
													<h5 class="card-title"><?= $user['name'];?></h5>
													<p class="card-text"><?= $user['email'];?></p>
												</div>
											</div>
										</div>
									</div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
