<div class="alert alert-alt alert-info">
    Available variable:
    <ul>
        @if (!isset($type) || (isset($type) && $type != 'invitation'))
            <li>%NAME% (Customer name)</li>
            <li>%EMAIL% (Customer email address)</li>
            <li>%SALUTATION% (Salutation)</li>
            <li>%CURRENT_RD_POINT% (Redeem point balance)</li>
            <li>%CURRENT_LD_POINT% (Lucky draw point balance) </li>
            <li>%TOTAL_SPENDING% (Total customer spending amount)</li>
        @endif
        @if (isset($type))
            @if (in_array($type, ['invitation', 'general']))
            <li>%INVITATION_LINK% (Generated register invitation link) <font class="bold">*Only works for Invitation Notif</font></li>
            @endif

            @if (in_array($type, ['redeem', 'earning', 'general']))
            <li>%RD_POINT% (Total redeem points spended or earned) <font class="bold">*Only works for Earning or Redeem Notif</font></li>
            @endif

            @if (in_array($type, ['earning', 'general']))
            <li>%LD_POINT% (Total lucky draw points earned) <font class="bold">*Only works for Earning Notif</font></li>
            <li>%LD_NUMBER% (Lucky draw number) <font class="bold">*Only works for Earning Notif</font></li>
            @endif
        @endif
    </ul>
</div>