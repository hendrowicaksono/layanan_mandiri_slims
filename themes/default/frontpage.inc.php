<?php
require_once 'header.inc.php';
?>

<a post="true" phref="http://localhost/dev/slims/mandiri/index.php/layanan_mandiri" pdata='{"name1": "value1","name2": "value2"}'>This is a post link</a>

<br/>
<a href="http://www.google.com">This is normal link</a>



<script type="text/javascript">
function makePostRequest(url, data) {
    var jForm = $('<form></form>');
    jForm.attr('action', url);
    jForm.attr('method', 'post');
    for (name in data) {
        var jInput = $("<input>");
        jInput.attr('name', name);
        jInput.attr('value', data[name]);
        jForm.append(jInput);
    }
    jForm.submit();
}

$(function(){
    $("a[post=true]").each(function () {
        $(this).on('click', function () {
            makePostRequest(
                $(this).attr('phref'),
                JSON.parse($(this).attr('pdata'))
            );
        });
    });
});

</script>
<?php
require_once 'footer.inc.php';
?>
