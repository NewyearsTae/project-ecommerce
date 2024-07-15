var product;

$(document).ready(() => {

    $.ajax({
        method: 'get',
        url: './api/getallproduct.php',
        success: function(response){
            console.log(response)
            if(response.RespCode == 200){
                product = response.Result
                var html = '';
                for (let i = 0; i < response.Result.length; i++) {
                    html += `<div class="productshow ${response.Result[i].type}">
                            <img class="product-img" src="images/product/${response.Result[i].img}">
                            <div class= "product-info">
                                <p style="font-size: 1.2vw;">${response.Result[i].name}</p>
                                <p style="font-size: 0.9vw;">${response.Result[i].price} THB</p>
                            </div>
                            </div>`;
                }
                $("#productlist").html(html);
                console.log(html)
            }
        }, error: function(err){
            console.log(err)
        }
    })

})