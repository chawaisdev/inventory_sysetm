(function () {
    flatpickr("#date", {}),
        flatpickr("#datetime", { enableTime: !0, dateFormat: "Y-m-d H:i" }),
        flatpickr("#humanfrienndlydate", { altInput: !0, altFormat: "F j, Y", dateFormat: "Y-m-d" }),
        flatpickr(".datepicker", { altInput: !0, altFormat: "F j, Y", dateFormat: "Y-m-d" }),
        flatpickr("#daterange", { mode: "range", dateFormat: "Y-m-d" }),
        flatpickr("#timepikcr", { enableTime: !0, noCalendar: !0, dateFormat: "H:i" }),
        flatpickr("#timepickr1", { enableTime: !0, noCalendar: !0, dateFormat: "H:i", time_24hr: !0 }),
        flatpickr("#limittime", { enableTime: !0, noCalendar: !0, dateFormat: "H:i", minTime: "16:00", maxTime: "22:30" }),
        flatpickr("#limitdatetime", { enableTime: !0, minTime: "16:00", maxTime: "22:00" }),
        flatpickr("#inlinecalendar", { inline: !0 }),
        flatpickr("#weeknum", { weekNumbers: !0 }),
        flatpickr("#inlinetime", { inline: !0, enableTime: !0, noCalendar: !0, dateFormat: "H:i" }),
        flatpickr("#pretime", { enableTime: !0, noCalendar: !0, dateFormat: "H:i", defaultDate: "13:45" });
})();
