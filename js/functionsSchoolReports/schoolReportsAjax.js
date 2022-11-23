$(document).ready(function () {
    $(".generateGroupReports").click(function () {
        const id_level_combination = $("#id_level_combination").val();
        
        switch (id_level_combination) {
            case '6':
                console.log("getBangFemSpanPrimSR()");
                break;

                case '7':
                console.log("getBangFemHebPrimSR()");
                break;
        
            default:
                break;
        }
    });
});
