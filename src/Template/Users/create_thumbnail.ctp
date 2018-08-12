<div class="users form large-9 medium-8 columns content">
	<?php echo $this->Form->create('', ['type'=>'file']); ?>
		<div>
	        <img id="image" src="<?php echo $this->Url->build('/webroot/img/test.jpg'); ?>">
	    </div>
	<?php echo $this->Form->end(); ?>
</div>
<div class="img-preview preview-lg"></div>
<br>
<button id="testBtn">Create Thumbnail</button>

<style type="text/css">
    img {
        max-width: 100%;
    }
</style>

<script type="text/javascript">
    var $image = $('#image');

    $image.cropper({
        dragMode: 'move',
        aspectRatio: 1 / 1,
        zoomable: false,
        cropBoxResizable: false,
        toggleDragModeOnDblclick: false,
        preview: '.img-preview',
        crop: function(event) {
        }
    });

    var cropper = $image.data('cropper');

    $('#testBtn').on('click', function(e) {
        e.preventDefault();
        var url = '<?php echo $this->Url->build("/users/uploadimage"); ?>';
        var user_id = '<?php echo $user_id; ?>';

        cropper.getCroppedCanvas().toBlob((blob) => {
            var formData = new FormData();
            formData.append('croppedImage', blob);
            formData.append('id', user_id);

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                headers : {
                    'X-CSRF-Token': $('[name="_csrfToken"]').val()
                },
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log(res);
                }
            });
        });
    });
</script>