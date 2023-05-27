<div class="modal fade" id="modalcaribarangpinjam" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Silahkan cari barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Silahkan cari barang berdasarkan Kode/Nama" id="caribarang">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" id="btnCariBarang">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="row viewdetailbarang"></div>
            </div>
        </div>
    </div>
</div>
<script>
    function crDataBarang() {
        let cari = $('#caribarang').val();
        $.ajax({
            type: "post",
            url: "/barangpinjam/detailCariBarang",
            data: {
                cari: cari
            },
            dataType: "json",
            beforeSend: function() {
                $('.viewdetailbarang').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if (response.data) {
                    $('.viewdetailbarang').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    $(document).ready(function() {
        $('#btnCariBarang').click(function(e) {
            e.preventDefault();
            crDataBarang();
        });

        //jika ditekan enter maka data yang dicari akan ditampilkan
        $('#caribarang').keydown(function(e) {
            if (e.keyCode == '13') {
                e.preventDefault();
                crDataBarang();
            }
        });
    });
</script>