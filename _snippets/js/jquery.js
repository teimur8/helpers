// Устанавливем значение select через js
var periodSelected = "{{ $data['period'] }}";
if(periodSelected != ''){
    $('[name=period] option[value='+periodSelected+']').attr('selected','selected');
}