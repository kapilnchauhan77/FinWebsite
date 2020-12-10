// Quaterly
//
var Days = 0;
var Year = parseInt($("#tenureYear").val());
var Month = Year * 12 + parseInt($("#tenureMon").val());
if ($('#tenureDays').val() != "") {
    Days = parseInt($('#tenureDays').val());
}
var temp = ($('#loan_amount').val());
var Principal = temp.replace(/\,/g, "");
var Yield = parseFloat($('.idRate').text()) / 100;
var Rate = parseFloat($('.idRate').text()) / 100;
console.log("Principal is " + Principal + " Yield is " + Yield + " Rate is " + Rate + " Month is " + Month + " Days are " + Days);
var Maturity = Math.round(Principal * (1 + (Yield * Month) / 12) + (Principal * Days * Rate) / 365);
console.log(Maturity);
if ($('#FdepType').val() == 'Short Term FD') {
    $(".MatureAmount").text(digits(Maturity));
}
else{
    $(".MatureAmount").text($(".loanAmt").val());
    $(".perQuaterCol").show();
var perQuatAmtGain = parseFloat(Principal*Yield*3/12);
$(".perQuaterAmt").text(digits(perQuatAmtGain.toFixed(0)));
}

var Interest = Maturity - Principal;
$(".InterestAmount").text(digits(Interest));
