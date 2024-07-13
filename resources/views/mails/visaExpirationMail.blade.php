@component('mail::message')
# Visa Expiration Notice 

@if(count($employees) >  0)
## ⚠ Important Notice  ⚠ 

###  We have identified a list of employees whose visas will expire within the next  10 days. Please review the details below:<br>

 @foreach($employees as $employee)
__Name (English):__  {{ $employee->name_en }} <br>
__Name (Arabic):__  {{ $employee->name_ar }} <br>
__Employee Code:__  {{ $employee->employee_code }} <br>
__Visa Expiration Date:__  {{ $employee->visa_expiry_date }} <br>

--------------------------<br>
 @endforeach

@else
##👌  All Clear  👌

### Good news! All employees have visas that will not expire within the next  10 days.<br>

###### You can relax and enjoy the rest of your day.<br>
@endif

Best regards,<br>
Al Rood Tech.<br>
@endcomponent
