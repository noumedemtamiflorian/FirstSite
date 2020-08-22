jQuery(document).ready(function () {
    delpanier = document.querySelectorAll("#delpanier");
    jQuery(delpanier).click(function (event) {
        event.preventDefault();
        href = jQuery(this).attr("href");
        href = href.replace("/shop/addpanier?","");
        href = href.replace("=",":");
        jQuery.get(jQuery(this).attr("href"), {href}, function (data) {
            console.log(data);
        }, 'text')
    })
});