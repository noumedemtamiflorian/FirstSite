jQuery(document).ready(function () {
    var products = document.querySelectorAll("#deletePanier");
    $(products).each(function (index, element) {
        $(this).click(function (e) {
            e.preventDefault();
            var confirmation = confirm("Etes vous sure de supprimer ce produit")
            if (confirmation) {
                var id = $(this).attr("alt");
                $.ajax({
                    type: "get",
                    url: "/shop/deletepanier",
                    data: "delete=" + id,
                    success: function (response) {
                        $("body").html(response);
                        alert("Produit  supprimer du panier")
                    },
                    error: function (reponse) {
                        alert("Produit non supprimer du panier")
                    }
                });
            } else {
                alert("Produit non supprimer du panier")
            }
        });
    });
}); 