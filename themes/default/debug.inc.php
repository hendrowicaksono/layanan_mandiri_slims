<?php
require_once 'header.inc.php';
?>

<a href="http://example.com" class="confirm">Example</a>

<script type="text/javascript">
$('.confirm').click(function(event){
    event.preventDefault();
    var href = $(this).attr('href');
    vex.dialog.confirm({
        message: 'Are you sure you want to visit this site?<br>' + href,
        callback: function(confirmed) {
            if (confirmed) {
                window.location.href = href;
            }
        }
    });
});
</script>

<button class="uk-button uk-button-primary" type="submit">Ini button nya</button>

<?php
require_once 'footer.inc.php';
?>