$(document).ready(function () {
  var $wrapper = $(".main-wrapper");
  var $slimScrolls = $(".slimscroll");
  var $pageWrapper = $(".page-wrapper");
  feather.replace();
  $(window).resize(function () {
    if ($(".page-wrapper").length > 0) {
      var height = $(window).height();
      $(".page-wrapper").css("min-height", height);
    }
  });
  $("body").append('<div class="sidebar-overlay"></div>');
  $(document).on("click", "#mobile_btn", function () {
    $wrapper.toggleClass("slide-nav");
    $(".sidebar-overlay").toggleClass("opened");
    $("html").addClass("menu-opened");
    $("#task_window").removeClass("opened");
    return false;
  });
  $(".sidebar-overlay").on("click", function () {
    $("html").removeClass("menu-opened");
    $(this).removeClass("opened");
    $wrapper.removeClass("slide-nav");
    $(".sidebar-overlay").removeClass("opened");
    $("#task_window").removeClass("opened");
  });
  $(document).on("click", ".hideset", function () {
    $(this).parent().parent().parent().hide();
  });
  $(document).on("click", ".delete-set", function () {
    $(this).parent().parent().hide();
  });
  if ($(".product-slide").length > 0) {
    $(".product-slide").owlCarousel({
      items: 1,
      margin: 30,
      dots: false,
      nav: true,
      loop: false,
      responsiveClass: true,
      responsive: { 0: { items: 1 }, 800: { items: 1 }, 1170: { items: 1 } },
    });
  }
  if ($(".owl-product").length > 0) {
    var owl = $(".owl-product");
    owl.owlCarousel({
      margin: 10,
      dots: false,
      nav: true,
      loop: false,
      touchDrag: false,
      mouseDrag: false,
      responsive: { 0: { items: 2 }, 768: { items: 4 }, 1170: { items: 8 } },
    });
  }
  if ($(".datanew").length > 0) {
    $(".datanew").DataTable({
      bFilter: true,
      sDom: "fBtlpi",
      pagingType: "numbers",
      ordering: true,
      language: {
        search: " ",
        sLengthMenu: "_MENU_",
        searchPlaceholder: "Tìm kiếm...",
        info: "_START_ - _END_ trên _TOTAL_ mục",
      },
      initComplete: (settings, json) => {
        $(".dataTables_filter").appendTo("#tableSearch");
        $(".dataTables_filter").appendTo(".search-input");
      },
    });
  }
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $("#blah").attr("src", e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
  $("#imgInp").change(function () {
    readURL(this);
  });
  if ($(".datatable").length > 0) {
    $(".datatable").DataTable({ bFilter: false });
  }
  setTimeout(function () {
    $("#global-loader");
    setTimeout(function () {
      $("#global-loader").fadeOut("slow");
    }, 100);
  }, 500);
  if ($(".datetimepicker").length > 0) {
    $(".datetimepicker").datetimepicker({
      format: "DD-MM-YYYY",
      icons: {
        up: "fas fa-angle-up",
        down: "fas fa-angle-down",
        next: "fas fa-angle-right",
        previous: "fas fa-angle-left",
      },
    });
  }
  if ($(".toggle-password").length > 0) {
    $(document).on("click", ".toggle-password", function () {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $(".pass-input");
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
  }
  if ($(".toggle-passwords").length > 0) {
    $(document).on("click", ".toggle-passwords", function () {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $(".pass-inputs");
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
  }
  if ($(".toggle-passworda").length > 0) {
    $(document).on("click", ".toggle-passworda", function () {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $(".pass-inputs");
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
  }
  if ($(".select").length > 0) {
    $(".select").select2({ minimumResultsForSearch: -1, width: "100%" });
  }
  if ($(".counter").length > 0) {
    $(".counter").counterUp({ delay: 20, time: 2000 });
  }
  if ($("#timer-countdown").length > 0) {
    $("#timer-countdown").countdown({
      from: 180,
      to: 0,
      movingUnit: 1000,
      timerEnd: undefined,
      outputPattern: "$day Day $hour : $minute : $second",
      autostart: true,
    });
  }
  if ($("#timer-countup").length > 0) {
    $("#timer-countup").countdown({ from: 0, to: 180 });
  }
  if ($("#timer-countinbetween").length > 0) {
    $("#timer-countinbetween").countdown({ from: 30, to: 20 });
  }
  if ($("#timer-countercallback").length > 0) {
    $("#timer-countercallback").countdown({
      from: 10,
      to: 0,
      timerEnd: function () {
        this.css({ "text-decoration": "line-through" }).animate(
          { opacity: 0.5 },
          500
        );
      },
    });
  }
  if ($("#timer-outputpattern").length > 0) {
    $("#timer-outputpattern").countdown({
      outputPattern: "$day Days $hour Hour $minute Min $second Sec..",
      from: 60 * 60 * 24 * 3,
    });
  }
  if ($("#summernote").length > 0) {
    $("#summernote").summernote({
      height: 300,
      minHeight: null,
      maxHeight: null,
      focus: true,
    });
  }
  if ($slimScrolls.length > 0) {
    $slimScrolls.slimScroll({
      height: "auto",
      width: "100%",
      position: "right",
      size: "7px",
      color: "#ccc",
      wheelStep: 10,
      touchScrollStep: 100,
    });
    var wHeight = $(window).height() - 60;
    $slimScrolls.height(wHeight);
    $(".sidebar .slimScrollDiv").height(wHeight);
    $(window).resize(function () {
      var rHeight = $(window).height() - 60;
      $slimScrolls.height(rHeight);
      $(".sidebar .slimScrollDiv").height(rHeight);
    });
  }
  var Sidemenu = function () {
    this.$menuItem = $("#sidebar-menu a");
  };
  function init() {
    var $this = Sidemenu;
    $("#sidebar-menu a").on("click", function (e) {
      if ($(this).parent().hasClass("submenu")) {
        e.preventDefault();
      }
      if (!$(this).hasClass("subdrop")) {
        $("ul", $(this).parents("ul:first")).slideUp(250);
        $("a", $(this).parents("ul:first")).removeClass("subdrop");
        $(this).next("ul").slideDown(350);
        $(this).addClass("subdrop");
      } else if ($(this).hasClass("subdrop")) {
        $(this).removeClass("subdrop");
        $(this).next("ul").slideUp(350);
      }
    });
    $("#sidebar-menu ul li.submenu a.active")
      .parents("li:last")
      .children("a:first")
      .addClass("active")
      .trigger("click");
  }
  init();
  $(document).on("mouseover", function (e) {
    e.stopPropagation();
    if ($("body").hasClass("mini-sidebar") && $("#toggle_btn").is(":visible")) {
      var targ = $(e.target).closest(".sidebar, .header-left").length;
      if (targ) {
        $("body").addClass("expand-menu");
        $(".subdrop + ul").slideDown();
      } else {
        $("body").removeClass("expand-menu");
        $(".subdrop + ul").slideUp();
      }
      return false;
    }
  });
  $(document).on("click", "#toggle_btn", function () {
    if ($("body").hasClass("mini-sidebar")) {
      $("body").removeClass("mini-sidebar");
      $(this).addClass("active");
      $(".subdrop + ul").slideDown();
      localStorage.setItem("screenModeNightTokenState", "night");
      setTimeout(function () {
        $("body").removeClass("mini-sidebar");
        $(".header-left").addClass("active");
      }, 100);
    } else {
      $("body").addClass("mini-sidebar");
      $(this).removeClass("active");
      $(".subdrop + ul").slideUp();
      localStorage.removeItem("screenModeNightTokenState", "night");
      setTimeout(function () {
        $("body").addClass("mini-sidebar");
        $(".header-left").removeClass("active");
      }, 100);
    }
    return false;
  });
  if (localStorage.getItem("screenModeNightTokenState") == "night") {
    setTimeout(function () {
      $("body").removeClass("mini-sidebar");
      $(".header-left").addClass("active");
    }, 100);
  }
  $(".submenus").on("click", function () {
    $("body").addClass("sidebarrightmenu");
  });
  $("#searchdiv").on("click", function () {
    $(".searchinputs").addClass("show");
  });
  $(".search-addon span").on("click", function () {
    $(".searchinputs").removeClass("show");
  });
  $(document).on("click", "#filter_search", function () {
    $("#filter_inputs").slideToggle("slow");
  });
  $(document).on("click", "#filter_search1", function () {
    $("#filter_inputs1").slideToggle("slow");
  });
  $(document).on("click", "#filter_search2", function () {
    $("#filter_inputs2").slideToggle("slow");
  });
  $(document).on("click", "#filter_search", function () {
    $("#filter_search").toggleClass("setclose");
  });
  $(document).on("click", ".productset", function () {
    $(this).toggleClass("active");
  });
  $(".inc.button").click(function () {
    var $this = $(this),
      $input = $this.prev("input"),
      $parent = $input.closest("div"),
      newValue = parseInt($input.val()) + 1;
    $parent.find(".inc").addClass("a" + newValue);
    $input.val(newValue);
    newValue += newValue;
  });
  $(".dec.button").click(function () {
    var $this = $(this),
      $input = $this.next("input"),
      $parent = $input.closest("div"),
      newValue = parseInt($input.val()) - 1;
    console.log($parent);
    $parent.find(".inc").addClass("a" + newValue);
    $input.val(newValue);
    newValue += newValue;
  });
  if ($(".custom-file-container").length > 0) {
    var firstUpload = new FileUploadWithPreview("myFirstImage");
    var secondUpload = new FileUploadWithPreview("mySecondImage");
  }
  $(".counters").each(function () {
    var $this = $(this),
      countTo = $this.attr("data-count");
    $({ countNum: $this.text() }).animate(
      { countNum: countTo },
      {
        duration: 2000,
        easing: "linear",
        step: function () {
          $this.text(Math.floor(this.countNum));
        },
        complete: function () {
          $this.text(this.countNum);
        },
      }
    );
  });
  if ($(".toggle-password").length > 0) {
    $(document).on("click", ".toggle-password", function () {
      $(this).toggleClass("fa-eye fa-eye");
      var input = $(".pass-input");
      if (input.attr("type") == "text") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
  }
  if ($(".win-maximize").length > 0) {
    $(".win-maximize").on("click", function (e) {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        }
      }
    });
  }
  $(document).on("click", "#check_all", function () {
    $(".checkmail").click();
    return false;
  });
  if ($(".checkmail").length > 0) {
    $(".checkmail").each(function () {
      $(this).on("click", function () {
        if ($(this).closest("tr").hasClass("checked")) {
          $(this).closest("tr").removeClass("checked");
        } else {
          $(this).closest("tr").addClass("checked");
        }
      });
    });
  }
  if ($(".popover-list").length > 0) {
    var popoverTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl);
    });
  }
  if ($(".clipboard").length > 0) {
    var clipboard = new Clipboard(".btn");
  }
  var chatAppTarget = $(".chat-window");
  (function () {
    if ($(window).width() > 991) chatAppTarget.removeClass("chat-slide");
    $(document).on(
      "click",
      ".chat-window .chat-users-list a.media",
      function () {
        if ($(window).width() <= 991) {
          chatAppTarget.addClass("chat-slide");
        }
        return false;
      }
    );
    $(document).on("click", "#back_user_list", function () {
      if ($(window).width() <= 991) {
        chatAppTarget.removeClass("chat-slide");
      }
      return false;
    });
  })();
  $(document).on("click", ".mail-important", function () {
    $(this).find("i.fa").toggleClass("fa-star").toggleClass("fa-star-o");
  });
  var selectAllItems = "#select-all";
  var checkboxItem = ":checkbox";
  $(selectAllItems).click(function () {
    if (this.checked) {
      $(checkboxItem).each(function () {
        this.checked = true;
      });
    } else {
      $(checkboxItem).each(function () {
        this.checked = false;
      });
    }
  });
  if ($('[data-bs-toggle="tooltip"]').length > 0) {
    var tooltipTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  }
  var right_side_views =
    '<div class="right-side-views d-none">' +
    '<ul class="sticky-sidebar siderbar-view">' +
    '<li class="sidebar-icons">' +
    '<a class="toggle tipinfo open-layout open-siderbar" href="javascript:void(0);" data-toggle="tooltip" data-placement="left" data-bs-original-title="Tooltip on left">' +
    '<div class="tooltip-five ">' +
    '<img src="assets/img/icons/siderbar-icon2.svg" class="feather-five" alt="">' +
    '<span class="tooltiptext">Check Layout</span>' +
    "</div>" +
    "</a>" +
    "</li>" +
    "</ul>" +
    "</div>" +
    '<div class="sidebar-layout">' +
    '<div class="sidebar-content">' +
    '<div class="sidebar-top">' +
    '<div class="container-fluid">' +
    '<div class="row align-items-center">' +
    '<div class="col-xl-6 col-sm-6 col-12">' +
    '<div class="sidebar-logo">' +
    '<a href="index.html" class="logo">' +
    '<img src="assets/img/logo.png" alt="Logo" class="img-flex">' +
    "</a>" +
    "</div>" +
    "</div>" +
    '<div class="col-xl-6 col-sm-6 col-12">' +
    '<a class="btn-closed" href="javascript:void(0);"><img class="img-fliud" src="assets/img/icons/sidebar-delete-icon.svg" alt="demo"></a>' +
    "</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    '<div class="container-fluid">' +
    '<div class="row align-items-center">' +
    '<h5 class="sidebar-title">Choose layout</h5>' +
    '<div class="col-xl-12 col-sm-6 col-12">' +
    '<div class="sidebar-image align-center">' +
    '<img class="img-fliud" src="assets/img/demo-one.png" alt="demo">' +
    "</div>" +
    '<div class="row">' +
    '<div class="col-lg-6 layout">' +
    '<h5 class="layout-title">Dark Mode</h5>' +
    "</div>" +
    '<div class="col-lg-6 layout dark-mode">' +
    '<label class="toggle-switch" for="notification_switch3">' +
    "<span>" +
    '<input type="checkbox" class="toggle-switch-input" id="notification_switch3">' +
    '<span class="toggle-switch-label ms-auto">' +
    '	<span class="toggle-switch-indicator"></span>' +
    "</span>" +
    "</span>" +
    " </label>" +
    "</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    $("body").append(right_side_views);
  $(".open-layout").on("click", function (s) {
    s.preventDefault();
    $(".sidebar-layout").addClass("show-layout");
    $(".sidebar-settings").removeClass("show-settings");
  });
  $(".btn-closed").on("click", function (s) {
    s.preventDefault();
    $(".sidebar-layout").removeClass("show-layout");
  });
  $(".open-settings").on("click", function (s) {
    s.preventDefault();
    $(".sidebar-settings").addClass("show-settings");
    $(".sidebar-layout").removeClass("show-layout");
  });
  $(".btn-closed").on("click", function (s) {
    s.preventDefault();
    $(".sidebar-settings").removeClass("show-settings");
  });
  $(".open-siderbar").on("click", function (s) {
    s.preventDefault();
    $(".siderbar-view").addClass("show-sidebar");
  });
  $(".btn-closed").on("click", function (s) {
    s.preventDefault();
    $(".siderbar-view").removeClass("show-sidebar");
  });
  if ($(".toggle-switch").length > 0) {
    const toggleSwitch = document.querySelector(
      '.toggle-switch input[type="checkbox"]'
    );
    const currentTheme = localStorage.getItem("theme");
    var app = document.getElementsByTagName("BODY")[0];
    if (currentTheme) {
      app.setAttribute("data-theme", currentTheme);
      if (currentTheme === "dark") {
        toggleSwitch.checked = true;
      }
    }
    function switchTheme(e) {
      if (e.target.checked) {
        app.setAttribute("data-theme", "dark");
        localStorage.setItem("theme", "dark");
      } else {
        app.setAttribute("data-theme", "light");
        localStorage.setItem("theme", "light");
      }
    }
    toggleSwitch.addEventListener("change", switchTheme, false);
  }
  if (window.location.hash == "#LightMode") {
    localStorage.setItem("theme", "dark");
  } else {
    if (window.location.hash == "#DarkMode") {
      localStorage.setItem("theme", "light");
    }
  }
  $("ul.tabs li").click(function () {
    var $this = $(this);
    var $theTab = $(this).attr("id");
    console.log($theTab);
    if ($this.hasClass("active")) {
    } else {
      $this
        .closest(".tabs_wrapper")
        .find("ul.tabs li, .tabs_container .tab_content")
        .removeClass("active");
      $(
        '.tabs_container .tab_content[data-tab="' +
          $theTab +
          '"], ul.tabs li[id="' +
          $theTab +
          '"]'
      ).addClass("active");
    }
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.querySelector('.custom-search-input');
  const optionsList = document.querySelector('.custom-options-list');
  const selectedCustomerInput = document.getElementById('selected-customer');

  // Lắng nghe sự kiện nhập văn bản vào ô tìm kiếm
  searchInput.addEventListener('input', function() {
    const searchQuery = searchInput.value.toLowerCase();
    
    // Lọc các mục trong danh sách khách hàng dựa trên từ khóa tìm kiếm
    const options = optionsList.querySelectorAll('.custom-option');
    options.forEach(function(option) {
      const customerName = option.textContent.toLowerCase();
      if (customerName.includes(searchQuery)) {
        option.style.display = 'block';  // Hiển thị các mục khớp với tìm kiếm
      } else {
        option.style.display = 'none';  // Ẩn các mục không khớp
      }
    });
  });

  // Lắng nghe sự kiện khi người dùng chọn một khách hàng
  optionsList.addEventListener('click', function(e) {
    const selectedOption = e.target;
    
    if (selectedOption.classList.contains('custom-option')) {
      // Cập nhật giá trị ô hiển thị
      const customerName = selectedOption.textContent;
      const customerId = selectedOption.getAttribute('data-value');
      document.querySelector('.custom-select-display').textContent = customerName;
      
      // Cập nhật giá trị ẩn
      selectedCustomerInput.value = customerId;
      
      // Đóng dropdown sau khi chọn khách hàng
      optionsList.style.display = 'none';
    }
  });

  // Đóng dropdown khi người dùng click ra ngoài
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-select-wrapper')) {
      optionsList.style.display = 'none';
    }
  });

  // Hiển thị lại danh sách khi người dùng click vào input
  searchInput.addEventListener('focus', function() {
    optionsList.style.display = 'block';
  });
});

document.addEventListener("DOMContentLoaded", function() {
  // Toggle dropdown
  document.addEventListener("click", function(e) {
    document.querySelectorAll(".custom-select-dropdown").forEach(drop => drop.style.display = "none");
    if (e.target.classList.contains("custom-select-display")) {
      const dropdown = e.target.nextElementSibling;
      dropdown.style.display = "block";
      dropdown.querySelector(".custom-search-input").focus();
      e.stopPropagation();
    }
  });

  // Lọc tùy chọn
  document.addEventListener("input", function(e) {
    if (e.target.classList.contains("custom-search-input")) {
      const keyword = e.target.value.toLowerCase();
      e.target.parentElement.querySelectorAll(".custom-option").forEach(opt => {
        const text = opt.textContent.toLowerCase();
        opt.style.display = text.includes(keyword) ? "" : "none";
      });
    }
  });

  // Chọn khách hàng hoặc sản phẩm
  document.addEventListener("click", function(e) {
    if (e.target.classList.contains("custom-option")) {
      const wrapper = e.target.closest(".custom-select-wrapper");
      const display = wrapper.querySelector(".custom-select-display");
      const dropdown = wrapper.querySelector(".custom-select-dropdown");
      display.textContent = e.target.textContent;
      dropdown.style.display = "none";

      // Nếu là khách hàng
      if (display.dataset.type === "customer") {
        document.getElementById("selected-customer").value = e.target.dataset.value;
      }

      // Nếu là sản phẩm (hoặc biến thể)
      if (display.dataset.type === "product") {
        const row = display.closest("tr");
        row.querySelector(".selected-product-id").value = e.target.dataset.id;
        row.querySelector(".product-price").textContent = parseInt(e.target.dataset.price).toLocaleString();
        updateTotals();
      }
    }
  });

  // Tính tổng
  function updateTotals() {
    let grandTotal = 0;
    document.querySelectorAll("#product-table tbody tr").forEach(row => {
      const price = parseFloat(row.querySelector(".product-price").textContent.replace(/\D/g, "")) || 0;
      const qty = parseInt(row.querySelector(".qty-input").value) || 1;
      const total = price * qty;
      row.querySelector(".product-total").textContent = total.toLocaleString();
      grandTotal += total;
    });
    document.getElementById("grand-total").textContent = grandTotal.toLocaleString();
    document.getElementById("tong-tien-input").value = grandTotal;
  }

  // Nút cộng trừ
  document.addEventListener("click", function(e) {
    if (e.target.classList.contains("btn-plus")) {
      const input = e.target.closest("tr").querySelector(".qty-input");
      input.value = parseInt(input.value) + 1;
      updateTotals();
    }
    if (e.target.classList.contains("btn-minus")) {
      const input = e.target.closest("tr").querySelector(".qty-input");
      if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
      updateTotals();
    }
    if (e.target.classList.contains("btn-remove")) {
      e.target.closest("tr").remove();
      updateTotals();
    }
  });

  // Thêm sản phẩm
  document.getElementById("add-product").addEventListener("click", function() {
    let index = document.querySelectorAll("#product-table tbody tr").length;
    let newRow = document.querySelector("#product-table tbody tr").cloneNode(true);
    newRow.querySelector(".selected-product-id").name = `products[${index}][id]`;
    newRow.querySelector(".qty-input").name = `products[${index}][qty]`;
    newRow.querySelector(".custom-select-display").textContent = "-- Chọn sản phẩm --";
    newRow.querySelector(".product-price").textContent = "0";
    newRow.querySelector(".product-total").textContent = "0";
    document.querySelector("#product-table tbody").appendChild(newRow);
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const updateTotals = () => {
    let grandTotal = 0;
    document.querySelectorAll("#product-table tbody tr").forEach(row => {
      const price = parseFloat(row.querySelector(".product-price").textContent.replace(/\D/g, "")) || 0;
      const qty = parseInt(row.querySelector(".qty-input").value) || 1;
      const total = price * qty;
      row.querySelector(".product-total").textContent = total.toLocaleString();
      grandTotal += total;
    });
    document.getElementById("grand-total").textContent = grandTotal.toLocaleString();
    document.getElementById("tong-tien-input").value = grandTotal;
  };

  // Cộng / trừ sản phẩm
  document.addEventListener("click", e => {
    if (e.target.classList.contains("btn-plus")) {
      const input = e.target.closest("tr").querySelector(".qty-input");
      input.value = parseInt(input.value) + 1;
      updateTotals();
    }
    if (e.target.classList.contains("btn-minus")) {
      const input = e.target.closest("tr").querySelector(".qty-input");
      if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
      updateTotals();
    }
    if (e.target.classList.contains("btn-remove")) {
      e.target.closest("tr").remove();
      updateTotals();
    }
  });

  // Áp dụng mã giảm giá
  document.getElementById("apply-voucher").addEventListener("click", async () => {
    const code = document.getElementById("voucher-code").value.trim();
    if (!code) return alert("Nhập mã giảm giá!");
    try {
      const res = await fetch(`/api/voucher/check/${code}`);
      const data = await res.json();

      if (data.status === "success") {
        const total = parseFloat(document.getElementById("tong-tien-input").value) || 0;
        let discount = (total * data.voucher.phan_tram / 100);
        if (discount > data.voucher.giam_toi_da) discount = data.voucher.giam_toi_da;

        const newTotal = total - discount;
        document.getElementById("grand-total").textContent = newTotal.toLocaleString();
        document.getElementById("tong-tien-input").value = newTotal;
        document.getElementById("voucher-id").value = data.voucher.id;
        document.getElementById("voucher-info").innerHTML = `Giảm ${data.voucher.phan_tram}% (tối đa ${data.voucher.giam_toi_da.toLocaleString()}đ)`;
      } else {
        alert("Mã không hợp lệ hoặc đã hết hạn!");
      }
    } catch {
      alert("Lỗi khi kiểm tra mã giảm giá!");
    }
  });

  updateTotals();
  
});