@extends('site.layouts.kiosk')

{{-- Web site Title --}}
@section('title')
KIOSK :: @parent
@stop
{{-- Content --}}
@section('content')
<link href="{{asset('assets/site/kiosk/css/component.css')}}" rel="stylesheet">
<link href="{{asset('assets/site/kiosk/css/component-modal.css')}}" rel="stylesheet">
<!-- <link href="{{asset('assets/site/kiosk/css/default.css')}}" rel="stylesheet"> -->

<div id="page-wrapper" style="background-color: #247878;">
    <br/>
    <div class="form-group">
        <div class="col-md-4">
            <input type="text" id="keyboard" class="form-control input-sm"></input>
        </div>
        <div class="col-md-8">
        </div>
        <!-- <button id="speak" type="button" class="btn btn-success btn-sm">SUBMIT</button> -->
    </div>
<br/>
<br/>
</div>
<!-- ==================MODAL================= -->
        <div class="md-modal md-effect-13" id="modal-1">
            <div class="md-content">
                <h3>CASHIER</h3>
                <div style="background-color: #fff !important;">
                    <p style="color: #103a5a !important;">Please select transaction!</p>
                    <ul>
                        <li>
                            <a ><strong> Payment </strong></a>
                        </li>
                        <li>
                            <a ><strong> Balance Inquiry </strong></a>
                        </li>
                    </ul>
                    <a style="color: #103a5a !important;" class="md-close">Cancel</a>
                </div>
            </div>
        </div>

        <div class="md-modal md-effect-9" id="modal-2">
            <div class="md-content">
                <h3 style="background-color: #E54F4F !important;color:#fff !important;">REGISTRAR</h3>
                <div style="background-color: #fff !important;">
                    <p style="color: #103a5a !important;">Please select transaction!</p>
                    <ul>
                        <li>
                            <a href="{{ URL::to('kiosk/register_student/create') }}"><strong>Student Registration</strong></a>
                        </li>
                        <li>
                            <strong>Enrollment</strong>
                        </li>
                        <li>
                            <strong>Reserve Student</strong>
                        </li>
                    </ul>
                    <a style="color: #103a5a !important;" class="md-close">Cancel</a>
                </div>
            </div>
        </div>
<!-- ==================MODAL================= -->
<div class="md-overlay" style="opacity: 0.3 !important;"></div>
<div class="md-overlay1" ></div>
<center style="background-color: #565656;">
      <ul class="grid cs-style-4">
          <li>
            <figure>
              <div><img src="{{asset('assets/site/kiosk/images/5.png')}}" alt="img05"></div>
              <figcaption>
                <h3>Cashier</h3>
                <span>- Payment</span><br/>
                <span>- Balance Inquiry</span>
                <a class="md-trigger" data-modal="modal-1" style="text-decoration: none;">Proceed</a>
              </figcaption>
            </figure>
          </li>
          <li>
            <figure>
              <div><img src="{{asset('assets/site/kiosk/images/6.png')}}" alt="img06"></div>
              <figcaption>
                <h3>Registrar</h3>
                <span>- Student Registration</span><br/>
                <span>- Enrollment</span><br/>
                <a class="md-trigger" data-modal="modal-2" style="text-decoration: none;">Proceed</a>
              </figcaption>
            </figure>
          </li>
          <li>
            <figure>
              <div><img src="{{asset('assets/site/kiosk/images/1.png')}}" alt="img02"></div>
              <figcaption>
                <h3>Information</h3>
                <span>- Student Registration</span><br/>
                <a class="md-trigger" data-modal="modal-13" style="text-decoration: none;">Proceed</a>
              </figcaption>
            </figure>
          </li>
          <li>
            <figure>
              <div><img src="{{asset('assets/site/kiosk/images/3.png')}}" alt="img04"></div>
              <figcaption>
                <h3>Other</h3>
                <span>- Test</span><br/>
                <a class="md-trigger" data-modal="modal-13" style="text-decoration: none;">Proceed</a>
              </figcaption>
            </figure>
          </li>
      </ul>
</center>

@stop
{{-- Scripts --}}
@section('scripts')
<script src="{{asset('assets/site/kiosk/js/modernizr.custom.js')}}"></script>
<script src="{{asset('assets/site/kiosk/js/toucheffects.js')}}"></script>
<script src="{{asset('assets/site/kiosk/js/classie.js')}}"></script>
<script src="{{asset('assets/site/kiosk/js/modalEffects.js')}}"></script>
<script type="text/javascript">
$(function() {

    // $("#keyboard").focus();

  $.widget("custom.catcomplete", $.ui.autocomplete, {
    _create: function() {
      this._super();
      this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
    },
    _renderMenu: function(ul, items) {
      var that = this,
        currentCategory = "";
      $.each(items, function(index, item) {
        var li;
        if (item.category != currentCategory) {
          ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
          currentCategory = item.category;
        }
        li = that._renderItemData(ul, item);
        if (item.category) {
          li.attr("aria-label", item.category + " : " + item.label);
        }
      });
    }
  });
  var data = [{
    label: "anders",
    category: ""
  }, {
    label: "andreas",
    category: ""
  }, {
    label: "antal",
    category: ""
  }, {
    label: "annhhx10",
    category: "Products"
  }, {
    label: "annk K12",
    category: "Products"
  }, {
    label: "annttop C13",
    category: "Products"
  }, {
    label: "anders andersson",
    category: "People"
  }, {
    label: "andreas andersson",
    category: "People"
  }, {
    label: "andreas johnson",
    category: "People"
  }];

  $('#keyboard')
    .keyboard()
    .catcomplete({
      delay: 0,
      source: data
    })
    .addAutocomplete({
      // custom autocomplete has unique data
      // To find the name, enter $('#keyboard').data()
      // in the console and look for a matching widget
      // data name
      data: 'catcomplete',
      events: 'catcomplete',
      // add autocomplete window positioning
      // options here (using position utility)
      position: {
        of: null,
        my: 'right top',
        at: 'left top',
        collision: 'flip'
      }
    })

  // activate the typing extension
  .addTyping({
    showTyping: true,
    delay: 250
  });
});

</script>
@stop