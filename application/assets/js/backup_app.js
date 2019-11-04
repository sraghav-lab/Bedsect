// Handles the Bedsect application initiation
function runApp(){
    $('#loading').modal('show');
    event.preventDefault();
    var gtrd;
    $('#get_exp tr').each(function(row, tr){gtrd = gtrd + $(tr).find('td:eq(0)').text()+','});
    gtrd = gtrd.replace('undefined,','');
    var user = $("#runAPP").val();
    var email = $("#email").val();
    var genome = $("#genome").val();
    var overlap = $("#overlap").val();
    $.ajax({
        type: "POST",
        url: "runJob.php",
        //url : "testApp.php",
        data: "user="+user+"&email="+email+"&genome="+genome+"&overlap="+overlap+"&gtrd="+gtrd,
        success: function(msg){
            //$("#alert").html(msg);
            //$('.modal').modal('show');
            $('#loading').modal('hide');
                $("#alert").html(msg);
        }
    });
};
//Enable or disable the GTRD panel
$(document).ready(function(){
    var gtrdSel = document.getElementById('gtrd-check');
    var gtrdElem = document.getElementById('orgSel');
    if (gtrdSel.checked == true){
	gtrdElem.removeAttr("disabled");
	gtrdElem.focus();
        // gtrdBox.style.display = "block";
    } else{
	// gtrdElem.attr("disabled", "disabled");
        // gtrdBox.style.display = "none";
    }
});

//Handle the transcription factor search function
$(document).ready(function(){
    //alert('this is great');
    $('#orgSel').on('change',function(){
        var organism = $('#orgSel').val();
        $.ajax({
            url:'gtrd_search.php',
            data:{organism:organism},
            type: 'POST',
            success:function(data){
                if(!data.error){
                    $('#tf_select').html(data);
                    $('#tf_select').selectpicker('refresh');
                }
            }
        });
    });
    $('#tf_select').on('change',function(){
        var organism = $('#orgSel').val();
        var tf_select = $('#tf_select').val();
        $.ajax({
            url:'gtrd_search.php',
            data:{organism:organism,search:tf_select},
            type: 'POST',
            success:function(data){
                if(!data.error){
                    $('#cell_select').html(data);
                    $('#cell_select').selectpicker('refresh');
                }
            }
        });
    });
    $('#cell_select').on('change',function(){
        var organism = $('#orgSel').val();
        var tf_select = $('#tf_select').val();
        var cell_select = $('#cell_select').val();
        $.ajax({
            url:'gtrd_search.php',
            data:{organism:organism,search:tf_select,cell:cell_select},
            type: 'POST',
            success:function(data){
                if(!data.error){
                    $('#gtrd_result').html(data);
                }
            }
        });
    });
    $('#gtrd_result').on('click','.btn-add',function(){
        var gtrdCheckout = document.getElementById('gtrd_added');
        var exp = $(this).parents("tr");
        exp.find('button').replaceWith('<button class="btn btn-danger btn-remove">Remove</button>');
        $('#gtrd_list').append(exp);
        gtrdCheckout.style.display = "block";
    });
    $('#gtrd_list').on('click','.btn-remove',function(){
        $(this).parents("tr").remove();
    });
});
