var product;

$(document).ready(() => {
    // รับค่ารายการสินค้าทั้งหมดผ่าน Ajax
    $.ajax({
        method: 'get',
        url: './api/getallproduct.php',
        cache: false,
        success: function(response) {
            console.log(response);
            if (response.RespCode == 200) {
                // เรียงลำดับรายการสินค้าตาม ID
                response.Result.sort((a, b) => a.id - b.id);
                var html = '';
                for (let i = 0; i < response.Result.length; i++) {
                    html += `<div class="product-item" data-product-id="${response.Result[i].id}">
                                <img class="imgproduct" src="/BackWeb/upload/${response.Result[i].img}">
                                <div class="product-info">
                                    <p style="font-size: 1.2vw;">${response.Result[i].name}</p>
                                    <p style="font-size: 1.2vw;">${response.Result[i].price} THB</p>
                                </div>
                            </div>`;
                }
                $("#productlist").html(html);

                // เพิ่มอีเวนต์ click สำหรับแสดง Modal เมื่อคลิกที่รูปสินค้า
                $(".product-item").click(function() {
                    var productId = $(this).data("product-id");
                    // แสดง Modal ที่มี ID เท่ากับ product-<product-id>
                    $("#product-" + productId).show();
                });

            }
        },
        error: function(err) {
            console.log(err);
        }
    });
});
