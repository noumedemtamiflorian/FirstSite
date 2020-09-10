jQuery(document).ready(function () {
   var products = document.querySelectorAll("#addPanier");
   $(products).each(function (index, element) {
      $(this).click(function (e) {
         e.preventDefault();
         var confirmation = confirm("Etes vous sure d'ajouter ce produit")
         if (confirmation) {
            var id = $(this).attr("alt");
            $.ajax({
               type: "get",
               url: "/shop/addpanier",
               data: "add=" + id,
               success: function (response) {
                  $("body").html(response);
                  alert("Produit  ajouter au panier")
               },
               error: function (reponse) {
                  alert("Produit non ajouter au panier")
               }
            });
         } else {
            alert("Produit non ajouter au panier")
         }
      });
   });
}); 