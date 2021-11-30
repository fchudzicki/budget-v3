
         $(document).ready(function(){

            $( ".single_expense" ).change(function() {
               var sum =0;
                $('.single_expense').each(function() {
                    sum += Number($(this).val());
                              });
                              $("#ins_sum").val(sum);
            })
        });