<?php

/*

Template Name: Custom Page Example

*/


	   //response generation function
	  $response = "";

	  //function to generate response
	  function my_contact_form_generate_response($type, $message){

		global $response;

		if($type == "success") $response = "<div class='success'>{$message}</div>";
	

	  }

  
	//response messages
	$message_sent    = "Thanks! Your message has been sent.";

	//user posted variables
	$name = $_POST['Name'];
	$email = $_POST['Email'];
	$notes = $_POST['Notes'];
	$artist_name = $_POST['Artist_Name'];
	$project_name = $_POST['Project_Name'];
	//remove "submitted" from $_POST array
	array_pop($_POST);
	//Define what is not to be considered as a track detail
	 $basic_info = array('Name','Email','Notes','Project_Name','Artist_Name');
		 
	 $num_tracks = 0;
	foreach ($_POST as $detail => $specific)
	{//If it's not one of the fields above
		if (!in_array($detail, $basic_info))
			//If no underscore it's a TRACK so goes in outer array
			if (!strpos($detail, "_"))
				{
				$num_deets = 0; // reset number of details
				$num_tracks++; // we have a new track
				//assign new top level key for track
				$tracks[$num_tracks][$detail] = $specific;
				$num_deets++; //we have one detail
				}
		else
				{
				//assign to secondary array of details
				$tracks[$num_tracks][$detail] = $specific;
				$num_deets++;//we have a new detail
				$detail = "\tDetail";
				}
				//add to the message with underscore removed
			$message .= "\n" . str_replace("_", " ", $detail).": ". $specific."\n";
		} 
			//php mailer variables
			$to = get_option('admin_email');
			$subject = "Album Details from $name via ".get_bloginfo('name');
			$headers = 'From: '. $email . "rn" .
			  'Reply-To: ' . $email . "rn";
				  
				  $sent = wp_mail($to, $subject, strip_tags($message), $headers);
				if($sent) my_contact_form_generate_response("success", $message_sent); //message sent!
				else my_contact_form_generate_response("error", $message_unsent); //message wasn't sent
										
?>

<?php do_action( '__before_main_wrapper' ); ##hook of the header with get_header ?>

<div id="main-wrapper" class="<?php echo tc__f( 'tc_main_wrapper_classes' , 'container' ) ?>">



    <?php do_action( '__before_main_container' ); ##hook of the featured page (priority 10) and breadcrumb (priority 20)...and whatever you need! ?>

    

    <div class="container" role="main">

        <div class="row">



            <?php do_action( '__before_article_container'); ##hook of left sidebar?>

                

                <div id="content" class="<?php echo tc__f( '__screen_layout' , tc__f ( '__ID' ) , 'class' ) ?> article-container">

                    

                    <?php do_action ('__before_loop');##hooks the header of the list of post : archive, search... ?>



                        <?php if ( have_posts() ) : ?>

                            <?php while ( have_posts() ) : ##all other cases for single and lists: post, custom post type, page, archives, search, 404 ?>

                                

                                <?php the_post(); ?>



                                <?php do_action ('__before_article') ?>

                                    <article <?php tc__f('__article_selectors') ?>>
                                    

                                        <?php do_action( '__loop' ); ?>
                                        

										<div id="respond">
										  <?php echo $response; ?>

     										<?php  //echo "<pre>$message</pre>"; ?>

			<?php //if we have tracks
			if (isset($tracks)){ 
			?>
			
			<h3>Feel free to print this page.</h3>
			<?php echo "Name: $name<br/>";
			echo "Artist: $artist_name<br/>";
			echo "Project: $project_name<br/>";
			
				$track_no = 0;
				foreach ($tracks as $track => $track_detail)
				{
				$track_no++;
				?>
				 <h3>Track <?php echo "$track_no"; ?> <span class="controlButtons">
				 </span></h3>
				 <?php foreach ($track_detail as $specific => $detail)
				 {
				 ?>
				 <?php 
				 echo "$detail<br/>"; ?>
				 <?php } ?>
				<?php
				}
				
			echo "<br/>Notes: ".$_POST['Notes']."<br/><br/>";
				
			}else{
        ?>

		  <form id="registration" action="<?php the_permalink(); ?>" method="post">
		 <p> Please enter CD-Text information to be added to the Production Master with desired capital letters. </p>
			<p><label for="name">Name: <span>*</span> <br><input type="text" name="Name" value="<?php echo esc_attr($_POST['Name']); ?>" data-validation="length" data-validation-length="min4"></label></p>
			<p><label for="message_email">Email: <span>*</span> <br><input type="text" name="Email" value="<?php echo esc_attr($_POST['Email']); ?>" data-validation="email" ></label></p>
			<p><label for="artist_name">Artist Name: <span>*</span> <br><input type="text" name="Artist_Name" value="<?php echo esc_attr($_POST['Artist_Name']); ?>" data-validation="length" data-validation-length="min1"></label></p>
			<p><label for="project_name">Project Name: <span>*</span> <br><input type="text" name="Project_Name" value="<?php echo esc_attr($_POST['Project_Name']); ?>" data-validation="length" data-validation-length="min1"></label></p>
											
											
 Add Track Titles. To add notes on fades, edits, multiple takes, ISRC Codes, etc, Click the '+' to the right 
<div id="content-contact">
    <div class="iteration">
       

        	 <h3>Track 1 <span class="controlButtons"><input type="button" value="+" class="plus" /><input type="button" value="-" class="moins" /></span></h3>
            <input type="text" value="Track Title" name="Track1"/>
    </div>
</div>
<input type="button" value="add track" id="addIteration" />
<input type="button" value="remove track" id="removeIteration" />	
											
			<p><label for="message_text">Notes:  <br><textarea type="text" name="Notes"><?php echo esc_textarea($_POST['Notes']); ?></textarea></label></p>
							

<p>Are you sure the above information is correct? (Check spelling and capitalization!)</p><br/>
<p>Yes. I am sure the above information is correct: <input data-validation="required"
data-validation-error-msg="CLICK TO CONFIRM ABOVE DETAILS, PLEASE" type="checkbox"></a> (check me)</p>

			<input type="hidden" name="submitted" value="1">
			<p><input type="submit" value="Submit Details"></p>
						  
					</form>		
				<?php } //EOF If we do not have $tracks (form not submitted) ?>
				
				</div>
<!--Re-enter Template -->
                                    </article>
	
									
									

                                <?php do_action ('__after_article') ?>



                            <?php endwhile; ?>



                        <?php endif; ##end if have posts ?>



                    <?php do_action ('__after_loop');##hook of the comments and the posts navigation with priorities 10 and 20 ?>



                </div><!--.article-container -->



           <?php do_action( '__after_article_container'); ##hook of left sidebar ?>



        </div><!--.row -->

    </div><!-- .container role: main -->



    <?php do_action( '__after_main_container' ); ?>



</div><!--#main-wrapper"-->
<script type="text/javascript">
	$(function(){
		var nbIteration = 1;
		var detailIteration = 1;
		$("#addIteration").click(function(){
			nbIteration++;
		var detailIteration = 1;
			$("#content-contact").append('<div class="iteration"><h3>Track '+ nbIteration +'<span class="controlButtons"><input type="button" value="+" class="plus" /><input type="button" value="-" class="moins" /></span></h3><input type="text" name="Track'+ nbIteration +'" value="Track Title" /></div>');
		});
		$("#removeIteration").click(function(){
			if($(".iteration").length > 0){
				nbIteration--;
				$(".iteration").last().remove();
			}
		});
		$("#content-contact").on("click", ".plus",function(){
			var parent = $(this).closest(".iteration");
			parent.append('<input type="text" value="Track Details" name="Track_'+ nbIteration + '_' + detailIteration +'"/>');
			detailIteration++;
			var nbinput = parent.find("input[type='text']").length;
			if(nbinput == 5)
				parent.find(".plus").prop("disabled",true);
			if(nbinput > 0)
				parent.find(".moins").prop("disabled",false);
		});
		$("#content-contact").on("click",".moins", function(){ 
			var parent = $(this).closest(".iteration");
			parent.children("input").last().remove();
			var nbinput = parent.find("input[type='text']").length;
			if(nbinput < 5)
				parent.find(".plus").prop("disabled",false);
			if(nbinput == 0)
				parent.find(".moins").prop("disabled",true);
		});
	});
</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>
 

<script> $.validate({
  form : '#registration'
}); </script>
<?php
		//Format arrays for display in development
			function pr($data)
			{
			    echo "<pre>";
			    print_r($data);
			    echo "</pre>";
			}
			?>
<?php do_action( '__after_main_wrapper' );##hook of the footer with get_get_footer ?>