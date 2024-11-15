/* sweetalert2 */
const swalert = (optionsMessage = {}, optionsSettings = {}) => {
    const defaultOptionsSettings = {
        toast: true,
        position: "top-end",
        iconColor: "white",
        showConfirmButton: false,
        timer: (() => {
            const msg = optionsMessage.title || optionsMessage.text || ""
            const calculatedTime = msg.split(" ").length * 1000
            return Math.max(calculatedTime, 2000)
        })(),
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer
            toast.onmouseleave = Swal.resumeTimer
        }
    }

    const Toast = Swal.mixin({ ...defaultOptionsSettings, ...optionsSettings })

    const defaultOptionsMessage = {
        icon: "info",
        customClass: {
            popup: "colored-toast",
        }
    }

    Toast.fire({ ...defaultOptionsMessage, ...optionsMessage })
}

/* Middleware */
const middlewares = []
const useMiddleware = (fn) => middlewares.push(fn)
const runMiddlewares = async () => { for (const fn of middlewares) await fn() }

$(document).ready(async () => {

    /* Base */
    const BASE_SERVER = $("html head base").attr("href")

    /* OverlayScrollbars */
    const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper"
    const Default = {
        scrollbarTheme: "os-theme-light",
        scrollbarAutoHide: "leave",
        scrollbarClickScroll: true
    }

    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER)

    if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined") {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
                theme: Default.scrollbarTheme,
                autoHide: Default.scrollbarAutoHide,
                clickScroll: Default.scrollbarClickScroll,
            }
        })
    }

    /* ViewTransition */
    if (window.navigation && document.startViewTransition) window.navigation.addEventListener("navigate", event => {
        const toUrl = new URL(event.destination.url)

        if (location.origin !== toUrl.origin) return

        event.intercept({
            async handler() {
                const $preloader = $(".app-preloader")

                document.startViewTransition(() => {
                    $preloader.addClass("active")
                    $("main.app-main").load(`${toUrl.pathname} main.app-main > *`, () => {
                        document.documentElement.scrollTop = 0
                        $preloader.removeClass("active")
                        runMiddlewares()
                    })
                })
            }
        })
    })

    /* ToggleSidebar */
    const toggleSidebar = () => {
        if (window.innerWidth <= 992) $("aside.app-sidebar .nav-link").on("click", event => {
            document.body.classList.toggle("sidebar-open")
            document.body.classList.toggle("sidebar-collapse")
        })
    }

    toggleSidebar()
    window.addEventListener("resize", toggleSidebar)

    /* Datatable */
    const loadDatatables = () => {
        $("table[data-action]").each(function () {
            const $table = $(this)
            const action = $table.data("action")

            $table.DataTable({
                ajax: action,
                processing: true,
                serverSide: true,
                responsive: true,
                layout: {
                    topStart: {
                        buttons: [
                            "copy", "excel", "csv", "pdf", "print", "pageLength"
                        ]
                    }
                },
                language: {
                    url: "./assets/plugins/datatables/language/es-ES.json",
                }
            })
        })
    }
    useMiddleware(loadDatatables)

    const refreshDatatables = () => {
        $("table.dataTable").each(function () {
            $(this).DataTable().ajax.reload();
        })
    };

    /* Form submit */
    $(document).on("submit", "form[data-action]", function (e) {
        e.preventDefault()

        const $form = $(this)
        const action = $form.data("action")

        $.ajax(action, {
            type: "POST",
            dataType: "JSON",
            data: new FormData(this),
            headers: { "CSRF-Token": $(".app-content").data("csrf") },
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: () => {
                $form.find("btn:submit").attr("disabled", true)
            },
            success: (response) => {
                const { status, message } = response;

                if (status) refreshDatatables()

                swalert({
                    icon: status ? "success" : "info",
                    title: message
                });
            },
            error: (jqXHR, textStatus) => {
                const { error } = jqXHR.responseJSON || {};

                swalert({
                    icon: "error",
                    title: error || textStatus
                });
            },
            complete: () => {
                $form.find("btn:submit").removeAttr("disabled")
                $(".modal.show").find(`[data-bs-dismiss="modal"]`).click()
                $form.get(0).reset()
            }
        })
    })

    runMiddlewares()
})