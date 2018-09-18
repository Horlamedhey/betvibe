<?php
require 'vendor/autoload.php';

use Josantonius\Session\Session;
Session::init();
if(!Session::get('email')){
  header("Location: index.php?loggedOut");
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="format-detection" content="telephone=no">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="viewport" content="user-scalable=yes,initial-scale=1,maximum-scale=4,minimum-scale=1,width=device-width">
  <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@^2.0.0/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@^3.5.2/animate.min.css" rel="stylesheet">
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/quasar-framework@^0.17.0/dist/umd/quasar.mat.min.css" rel="stylesheet" type="text/css">
    <link href="styles/styles.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/particles.min.js"></script>
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
</head>
<body>
  <style>
    label {
        display:block;
        margin-top:20px;
        letter-spacing:2px;
    }
    form {
      margin:0 auto;
    }
    input, textarea {
      width: 100%;
      height:27px;
      background:#efefef;
      border:1px solid #dedede;
      padding:10px;
      margin-top:3px;
      font-size:0.9em;
      color:#3a3a3a;
      -moz-border-radius:5px;
      -webkit-border-radius:5px;
      border-radius:5px;
    }
    input:focus, textarea:focus {
      border:1px solid #97d6eb;
    }
    textarea {
      height:213px;
      background:url(images/textarea.jpeg) center no-repeat #efefef;
      background-size: cover;
      resize: none;
    }
    #submit {
      font-weight: 900;
      width:127px;
      height:38px;
      background:url(images/textarea1.jpeg);
      border: none;
      margin-top:20px;
      cursor: pointer;
    }
    #submit:hover {
      opacity:9;
    }
    input:focus{
      border: none;
    }
    .q-loading-bar{
      background: green!important;
      height: 4px!important;
    }
    @media(min-width: 1000px){
      .title{
        font-size: 2.2em!important;
      }
      .subtitle{
        font-size: 1.7em!important;
      }
      .q-item-main{
        font-size: 1.2em!important;
      }
    }
    @media(min-width: 2000px){
      .title{
        font-size: 3.5em!important;
      }
      .subtitle{
        font-size: 4em!important;
      }
      .q-item-main{
        font-size: 2.5em!important;
      }
    }
  </style>
  <div id="q-app1">
    <q-layout>
      <q-layout-header style="padding: 5px;">
        <q-toolbar color="white" style="padding: 0;">
          <div class="row justify-center">
            <div class="col-xs-12 col-lg-6 col-xl-6">
              <img style="transition:0.5s;" id="logo" height="auto" width="100%" src="images/logo.jpg" alt="logo">
            </div>
            <q-toolbar-title style="animation-duration: 3s;" class="col-xs-12
                             animatedm fadeIn">
              <div style="font-size: 1.1em; text-align: center;" class="title
                   cursor-pointer brand text-weight-bold">Welcome <?php echo(Session::get('email'));?>!</div>
              <div style="font-size: 1em; text-align: center" slot="subtitle"
                    class="subtitle text-black ellipsis">
                Use this referral code: <span class="text-weight-bold"><?php echo(Session::get('ownReferralCode'));?></span> to earn more tokens.
              </div>
            </q-toolbar-title>
            <div class="row col-xs-12 justify-around">
              <q-item class="animated lightSpeedIn">
                <q-item-main class="text-black">TOKENS: <span class="text-weight-bold"><?php echo(Session::get('tokens') + (Session::get('referrals') * 5000));?></span></q-item-main>
              </q-item>
              <q-item class="animated lightSpeedIn">
                <q-item-main class="text-black">REFERRALS: <span class="text-weight-bold"><?php echo(Session::get('referrals'));?></span></q-item-main>
              </q-item>
            </div>
          </div>
        </q-toolbar>
      </q-layout-header>
      <q-page-sticky style="z-index: 1" position="bottom-right" :offset="[18, 18]">
        <q-btn round color="green" @click="window.location.href = 'scripts/logout.php'" icon="mdi-logout">
          <q-tooltip>Logout</q-tooltip>
        </q-btn>
      </q-page-sticky>
      <q-page-container class="q-mb-xl">
        <h3>Join Us:</h3>
        <div class="column items-center">
          <div class="row justify-around">
            <div @click="launch('https://medium.com/@betvibe')" class="media-logo"
                 style="background: #000000;">
              <q-icon name="mdi-medium" color="white" size="40px"></q-icon>
            </div>
            <div>
              <q-input v-model="medium" color="green" placeholder="Medium Username Here"></q-input>
            </div>
            <div>
              <q-btn label="Submit" :loading="mediaLoader.status && mediaLoader.active === 'medium'"
                     @click="mediaAdd({action: 'medium', data: medium})" style="height: 40%; display: inline-block;" color="positive">
                <q-spinner-ball slot="loading" size="20px"/>
              </q-btn>
            </div>
          </div>
          <div class="row justify-around">
            <div @click="launch('https://t.me/BetVibe')" class="media-logo" style="background: #31A9DD;">
              <q-icon name="mdi-telegram" color="white" size="40px"></q-icon>
            </div>
            <div>
              <q-input v-model="telegram" placeholder="Telegram Username Here"
                       color="green"></q-input>
            </div>
            <div>
              <q-btn label="Submit" :loading="mediaLoader.status && mediaLoader.active ===
                     'telegram'" @click="mediaAdd({action: 'telegram', data: telegram})" style="height: 40%; display: inline-block;" color="positive">
                <q-spinner-ball slot="loading" size="20px"/>
            </q-btn>
            </div>
          </div>
          <div class="row justify-around">
            <div @click="launch('https://twitter.com/betvibe_co')"
                 class="media-logo" style="background: #1DA1F2;">
              <q-icon name="mdi-twitter" color="white" size="40px"></q-icon>
            </div>
            <div>
              <q-field :error="twitterError" error-label="Must start with @">
                <q-input v-model="twitter" color="green" placeholder="Twitter Handle Here"></q-input>
              </q-field>
            </div>
            <div>
              <q-btn label="Submit" :loading="mediaLoader.status && mediaLoader.active ===
                     'twitter'" @click="mediaAdd({action: 'twitter', data: twitter})" style="height: 40%; display: inline-block;" color="positive">
                <q-spinner-ball slot="loading" size="20px"/>
              </q-btn>
            </div>
          </div>
        </div>
        <h6 style="text-decoration: underline;" class="q-pa-md text-center">Input your Ethereum public address here to claim your tokens:</h6>
        <div class="row justify-center">
          <div class="media-logo" style="background: rgba(0, 0, 0, 0.2);">
            <q-icon style="color: #78797A;" name="mdi-ethereum" size="40px"></q-icon>
          </div>
          <div>
            <q-input v-model="ethereum" color="tertiary" placeholder="Ethereum Address Here"></q-input>
          </div>
          <div>
            <q-btn label="Submit" :loading="mediaLoader.status && mediaLoader.active === 'ethereum'" @click="mediaAdd({action: 'ethereum', data: ethereum})" style="height: 40%; display: inline-block; background: #78797A;">
                <q-spinner-ball slot="loading" size="20px"/>
            </q-btn>
          </div>
        </div>
      </q-page-container>
      <q-layout-footer style="bottom: -200px; z-index: 1;" class="row q-pa-lg
                         justify-around">
          <div class="col-xs-12 col-md-6 col-lg-4 q-pa-lg">
            <h6 class="text-center">Join the Conversation</h6>
            <div class="row justify-around">
            <div @click="launch('https://medium.com/@betvibe')" class="media-logo"
                 style="background: #000000;">
              <q-icon name="mdi-medium" color="white" size="40px"></q-icon>
            </div>
              <div @click="launch('https://t.me/BetVibe')" class="media-logo" style="background: #31A9DD;">
                <q-icon name="mdi-telegram" color="white" size="40px"></q-icon>
              </div>
              <div @click="launch('https://twitter.com/betvibe_co')" style="background: #1DA1F2;" class="media-logo">
                <q-icon name="mdi-twitter" color="white" size="40px"></q-icon>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-4 q-pa-lg ">
            <h6 class="text-center">Enquiries/Complains</h6>
            <p class="foot text-center cursor-pointer" @click="modal = true">Support@betvibe.co</p>
            <q-modal v-model="modal" minimized ref="modalRef">
              <div class="row justify-center" style="padding: 50px">
                <div class="col-xs-12">
                  <div class="col-xs-12">
                    <div class="column">
                      <div>
                        <label>Name</label>
                        <input v-model="senderName" name="name" placeholder="Type Here">
                      </div>
                      <div>  
                        <label>Email</label>
                        <input v-model="email" name="email" type="email" placeholder="Type Here">
                      </div>
                      <div>
                        <label>Message</label>
                        <textarea v-model="message" name="message" placeholder="Type Here"></textarea>
                      </div>
                      <div class="col-xs-10 q-mt-md">
                        <q-btn @click="support()" style="margin: auto; display: block;" id="submit" name="submit" value="Submit">Submit</q-btn>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12">
                  <q-btn flat round class="absolute-top-right" v-close-overlay>
                    <q-icon color="red" name="mdi-close"></q-icon>
                  </q-btn>
                </div>
              </div>
              <q-inner-loading :visible="loader">
                <q-spinner-gears size="60px" color="green"></q-spinner-gears>
              </q-inner-loading>
            </q-modal>
          </div>
          <div class="justify-between column col-xs-12 col-md-6 col-lg-4 q-pa-lg">
            <h6 class="foot cursor-pointer text-center" @click="launch('files/tandc.pdf')">Terms & Conditions</h6>
            <h6 style="margin-top: 25px;" @click="faq()" class="foot cursor-pointer text-center">FAQ</h6>
            <q-modal :content-css="{minWidth: '100vw', minHeight: '100vh'}"
                    v-model="opened">
              <q-modal-layout>
                <q-toolbar style="padding: 0;" slot="header" color="green">
                  <img @dblclick="login('admin')" class="mobile-only" width="100%" src="images/logo.jpg" alt="logo">
                  <q-toolbar-title class="q-headline desktop-only">
                    <img @dblclick="login('admin')" class="absolute-center" width="30px" src="images/logo-dark.jpg" alt="logo">
                    Betvibe.co
                  </q-toolbar-title>
                  <q-btn color="negative" class="absolute-top-right" flat round dense v-close-overlay
                         icon="mdi-close"></q-btn>
                </q-toolbar>
            
                <q-toolbar slot="footer" color="green">
                  <q-toolbar-title class="text-center">
                    &COPY; 2018 betvibe
                  </q-toolbar-title>
                </q-toolbar>
            
                <div class="layout-padding">
                  <h3>FAQ</h3>
                  <q-list>
                    <q-collapsible v-for="faq in faqs" group="faq" :icon="faq.icon" :label="faq.question">
                      <div>
                        {{faq.answer}}
                      </div>
                    </q-collapsible>
                  </q-list>
                </div>
              </q-modal-layout>
              <q-inner-loading :visible="loader">
                <q-spinner-gears size="60px" color="green"></q-spinner-gears>
              </q-inner-loading>
          </q-modal>
          </div>
          <div class="row justify-around q-mb-xl q-mt-xl">
            <h5 class="col-xs-11 text-center" style="text-decoration: underline;">Exchange Targets</h5>
            <div class="col-xs-7  col-sm-5 col-xl-2">
              <img height="auto" width="100%" src="images/exchange3.jpeg" alt="logo">
            </div>
            <div class="col-xs-7  col-sm-5 col-xl-2">
              <img height="auto" width="100%" src="images/exchange2.jpeg" alt="logo">
            </div>
            <div class="col-xs-7  col-sm-5 col-xl-2">
              <img height="auto" width="100%" src="images/exchange4.jpeg" alt="logo">
            </div>
            <div class="col-xs-7 col-sm-5 col-xl-2">
              <img height="auto" width="100%" src="images/exchange1.jpeg" alt="logo">
            </div>
          </div>
          <div class="row justify-center q-mb-xl">
            <div class="col-xs-11 col-md-8 col-lg-6">
              <img height="auto" width="100%" src="images/logo.jpg" alt="logo">
            </div>
          </div>
          <div class="col-xs-12 text-center">&COPY; 2018 Betvibe</div>
      </q-layout-footer>
    </q-layout>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/quasar-framework@^0.17.0/dist/umd/quasar.ie.polyfills.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@latest/dist/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quasar-framework@^0.17.0/dist/umd/quasar.mat.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quasar-framework@^0.17.0/dist/umd/icons.mdi.umd.min.js"></script>
    <script
      src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script>
      Quasar.icons.set(Quasar.icons.mdi)
      var betvibe = new Vue({
        el: '#q-app1',
        data: function () {
          return {
            opened: false,
            modal: false,
            loader: false,
            senderName: '',
            message: '',
            email: '',
            mediaLoader: {status: false, active: 'medium'},
            ethereum: '',
            medium: '',
            telegram: '',
            twitter: '',
            twitterError: false,
            faqs: []
          }
        },
        methods: {
          support () {
            let name = this.senderName,
            email = this.email,
            message = this.message
            if (name === '' || email === '' || message === '') {
              this.$q.notify({
                message: 'Please fill all fields!',
                timeout: 700,
                type: 'negative',
                position: 'top'
              })
            } else if (!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email)) {
              this.$q.notify({
                message: 'Invalid Email Address!',
                timeout: 700,
                type: 'negative',
                position: 'top'
              })
              } else {
                this.loader = true
                $.ajax({
                  type: 'POST',
                  url: 'scripts/support.php',
                  data: {
                    action: 'support',
                    name: betvibe.senderName,
                    email: betvibe.email,
                    message: betvibe.message
                  },
                  success: function(response) {
                    betvibe.loader = false
                    if (/Success/.test(response)) {
                      betvibe.senderName = ''
                      betvibe.email = ''
                      betvibe.message = ''
                    }
                    betvibe.$q.notify({
                      message: response,
                      timeout: 700,
                      type: /Success/.test(response) ? 'positive' : 'negative',
                      position: 'top'
                    })
                  }
                })
            }
          },
          faq () {
            this.opened = true
            this.loader = true
              let a = []
            $.getJSON('files/faq.json', result => {
              new Promise((resolve, reject) => {
                resolve(
                  result.data.forEach(v => {
                    betvibe.faqs.push(v)
                  })
                )
              }).then(() => {
                setTimeout(() => {
                  this.loader = false
                }, 1000);
              })
            })
          },
          media (arg) {
            betvibe.mediaLoader.active = arg.action
            betvibe.mediaLoader.status = true
            let note = this.$q.notify
            $.ajax({
              type: 'POST',
              url: 'scripts/media.php',
              data: {
                address: arg.data,
                action: arg.action
              },
              cache: false,
              success: (response) => {
                note({
                message: response,
                timeout: 500,
                type: /Success/.test(response) ? 'positive' : 'negative',
                position: 'top'
              })
              setTimeout(() => {
                betvibe.mediaLoader.active = ''
                betvibe.mediaLoader.status = false
              }, 1000)
            }
          })
          },
          mediaAdd (arg) {
            if (arg.data !== '') {
              if (arg.action === 'twitter') {
                if (/^@/.test(arg.data)) {
                  this.twitterError = false
                  this.media(arg)
                } else {
                  this.twitterError = true
                }
              } else {
                this.media(arg)
              }
            }
          },
          launch (url) {
            Quasar.utils.openURL(url)
          }
        }
      })
    </script>
  <script type="text/javascript">
    betvibe.medium = '<?php echo(Session::get('medium'));?>';
    betvibe.telegram = '<?php echo(Session::get('telegram'));?>';
    betvibe.twitter = '<?php echo(Session::get('twitter'));?>';
    betvibe.ethereum = '<?php echo(Session::get('ethereum'));?>';
  </script>
</body>
</html>