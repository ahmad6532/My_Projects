function passwordDisplay() {
  var x = document.getElementById("pass");
  var y= document.getElementById("cpass");
  if (x.type === "password"|| y.type==="password") {
    x.type = "text";
    y.type="text";
  } else {
    x.type = "password";
    y.type="password";
  }
}

// window.onload= function (){
//     document.getElementById('close_shift').hidden=true;
//     document.getElementById('save').hidden=true;
//
// }

$(document).ready(function() {
    var table = $('#dataTables').DataTable();

    $('#dataTables tbody').on('click', 'td', function () {
        var colIdx = table.cell(this).index().row;
        $( table.rows().nodes() ).removeClass( 'highlight' );
        $( table.row( colIdx ).nodes() ).addClass( 'highlight' );
        var id = table.row($(this).closest('tr')).data()[0];
        var points = table.row($(this).closest('tr')).data()[3];
        var mod=points%100;
        var remaining_points=points-mod;
        var amount=remaining_points/100;
        var link = document.getElementById('reeeeedeem');

            if (points >=100)
            {
                link.setAttribute("onclick", "location.href='redeem-points?account_id=" + id + "&points=" + remaining_points + "&tpoints=" + points + "&amount=" + amount + "'");

            }
            else
            {
                link.setAttribute("onclick", "Clearnode()");
            }



        //alert("hello");
        // var lastRefill = document.getElementById('lastRefill');
        // lastRefill.setAttribute("onclick", "location.href='canceltransaction.php?account_id=" + id+ "&cancelRefill= true'");
        var valll = document.getElementById('count').value;
        var vall = valll.replace('$','');
        var valls = vall*100;
        var fill = document.getElementById('reeefill');

        if(vall>0){
            fill.setAttribute("onclick", "location.href='add-player-credits?account_id=" + id+ "&val="+valls +"'");
        }
        else{
            fill.setAttribute("onclick", "ClearNode()");
        }

    });



    $("#searchme").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var oTable = $('#dataTables').dataTable();
        //alert( document.querySelector('#searchme').value);
        oTable.fnFilter(document.querySelector('#searchme').value);
    });
});

$("input[data-type='currency']").on({
    keyup: function() {
        formatCurrency($(this));
    },
    blur: function() {
        formatCurrency($(this), "blur");
    }
});


function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.

    // get input value
    var input_val = input.val();

    // don't validate empty input
    if (input_val === "") { return; }

    // original length
    var original_len = input_val.length;

    // initial caret position
    var caret_pos = input.prop("selectionStart");

    // check for decimal
    if (input_val.indexOf(".") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(".");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);

        // On blur make sure 2 numbers after decimal
        if (blur === "blur") {
            right_side += "00";
        }

        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val = "$" + left_side + "." + right_side;

    } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);
        input_val = "$" + input_val;


    }

    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
}
function ClearNode(){
    var table = $('#dataTables').DataTable();

    swal("Alert!", 'First fill an amount and then select a player before clicking refill button!', "error");

    $( table.rows().nodes() ).removeClass( 'highlight' );
    var fill = document.getElementById('reeefill');
    fill.setAttribute("onclick", "");
}
function Clearnode(){
    var table = $('#dataTables').DataTable();
    swal("Alert!", 'Points must be greater than 99', "error");

    $( table.rows().nodes() ).removeClass( 'highlight' );
}
function SelectPlayerFirst(){
    var table = $('#dataTables').DataTable();
    swal("Alert!", 'First fill an amount and then select a player before clicking refill button!', "error");
}


$(document).ready(function(){
    $('#range').click(function(){
        var From = $('#fdate').val();
        var to = $('#tdate').val();
        if(From == '' || to == '')
        {

            swal("Alert!", 'Please Select the Date!', "error");
            return false;
        }
        else {
            return ture;
        }
    });
});
function cap() {


    var alpha=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V'
        ,'W','X','Y','Z','1','2','3','4','5','6','7','8','9','0','a','b','c','d','e','f','g','h','i',
        'j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];

    var a=alpha[Math.floor(Math.random()*62)];
    var b=alpha[Math.floor(Math.random()*62)];
    var c=alpha[Math.floor(Math.random()*62)];
    var d=alpha[Math.floor(Math.random()*62)];
    var e=alpha[Math.floor(Math.random()*62)];
    var f=alpha[Math.floor(Math.random()*62)];

    var sum=a + b + c + d + e + f;

    document.getElementById("capt").value=sum;
    var symbol="0123456789ABCDEF";
    var color="#";
    for (var i=0; i<6;i++)
    {
        color=color + symbol[Math.floor(Math.random()*16)];
    }
    document.getElementById("capt").style.color=color;
}

function validcap() {
    var string1 = document.getElementById("capt").value;
    var string2 = document.getElementById("captcha_code").value;
if (string2 != "") {
    if (string1 == string2) {
        return true;
    } else {
        document.getElementById("error").innerHTML = "Invalid Captcha Code";
        return false;
    }
}
}
window.onload=cap;
