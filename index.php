<!DOCTYPE html>
<html lang="en-us">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="viewport" content="user-scalable=yes,initial-scale=1,maximum-scale=4,minimum-scale=1,width=device-width">

    <title>Betvibe.co</title>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@^2.0.0/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@^3.5.2/animate.min.css" rel="stylesheet">
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/quasar-framework@^0.17.0/dist/umd/quasar.mat.min.css" rel="stylesheet" type="text/css">
    <link href="styles/styles.css" rel="stylesheet" type="text/css">

            <script src="scripts/moment.min.js"></script>
            <script src="scripts/moment.js"></script>
    <style type="text/css">
      .logo-container {
        width: 255px;
        height: 242px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateX(-50%) translateY(-50%);
      }
      .logo {
        position: absolute;
      }
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
      .foot{
        width: fit-content;
        margin: auto;
      }
    </style>
  </head>
  <body>
    <?php
    if (isset($_GET['loggedOut'])) :
    ?>
    <script>
      document.addEventListener('readystatechange', () => {
        if (document.readyState === 'complete') {
          document.getElementById('loginBtn').click()
        }
      })
    </script>
    <?php
    endif;
    ?>
    <div id="q-app">
      <q-layout view="lHh Lpr fff" @scroll="reactScroll()">
        <q-layout-header style="z-index: 1;">
          <q-toolbar :color="navColor">
            <q-toolbar-title style="animation-duration: 3s;" class="animated fadeIn">
              <div class="row justify-between" style="width:165px;margin-top: 10px;">
                  <img style="transition:0.5s;" id="logo" height="40px;" width="30px" src="images/logo-dark.jpg" alt="logo">
                  <div class="cursor-pointer brand q-display-1 text-weight-bold">Betvibe</div>
              </div>
              <div style="font-size: 16px;" slot="subtitle"
                   class="text-weight-bolder text-white ellipsis" id="subtitle">
                Sport betting has never been more profitable...
              </div>
            </q-toolbar-title>
            <div class="row desktop-only">
              <q-item class="animated lightSpeedIn cursor-pointer whitepaper"
                      @click.native="launch('files/whitepaper.pdf')">
                <q-item-main id="whitepaper" label="WHITEPAPER"></q-item-main>
              </q-item>
              <q-item class="animated lightSpeedIn cursor-pointer whitepaper"
                      @click.native="launch('files/tokenmetrics.pdf')">
                <q-item-main id="tokenmetrics" label="TOKEN METRICS"></q-item-main>
              </q-item>
              <q-item class="animated lightSpeedIn cursor-pointer navItems"
                      @click.native="goTo(item.link)" v-for="(item, i) in navItems">
                <q-item-main :label="item.name"></q-item-main>
              </q-item>
            </div>
            <q-btn :color="menuColor" class="mobile-only" flat round dense @click="drawerState = !drawerState" icon="mdi-menu"></q-btn>
          </q-toolbar>
        </q-layout-header>

        <q-layout-drawer class="mobile-only" side="right"
          v-model="drawerState"
          :content-class="$q.theme === 'mat' ? 'bg-black' : null"
        >
          <q-list highlight no-border link inset-delimiter>
            <q-list-header>Navigation</q-list-header>
            <q-item @click.native="launch('files/whitepaper.pdf')"
                    style="width: 50%" class="cursor-pointer whitepaper">
              <q-item-main id="whitepaper" label="WHITEPAPER"></q-item-main>
            </q-item>
            <q-item @click.native="launch('files/tokenmetrics.pdf')"
                    style="width: 50%" class="cursor-pointer whitepaper">
              <q-item-main id="tokenmetrics" label="TOKEN METRICS"></q-item-main>
            </q-item>
            <q-item class="cursor-pointer navItems" 
                    @click.native="goTo(item.link)" v-for="item in navItems">
              <q-item-main class="text-white" :label="item.name"></q-item-main>
            </q-item>
          </q-list>
          <div style="width: fit-content; margin: auto;" class="row">
          <q-btn style="color: white;" class="med text-weight-bolder q-mr-sm" size="sm" @click="openForm('register')">
                 register
          </q-btn>
          <q-btn style="color: white;" class="med text-weight-bolder q-mr-sm" size="sm" id="loginBtn" @click.stop="openForm('login')">
                 login
          </q-btn>
          <q-btn style="color: white;"  class="med text-weight-bolder" size="sm" @click="launch('dashboard.php')">
            dashboard
          </q-btn>
        </div>
        </q-layout-drawer>
        <div style="z-index: 1; position: fixed; right: 18px; top: 95px"
             class="desktop-only row">
          <q-btn :color="menuColor" :style="menuColor === 'white' ? 'color: black!important' : 'color: white!important'" class="text-weight-bolder q-mr-sm" size="sm" @click="openForm('register')" glossy>
                 register
          </q-btn>
          <q-btn :color="menuColor" :style="menuColor === 'white' ? 'color: black!important' : 'color: white!important'" class="text-weight-bolder q-mr-sm" size="sm" id="loginBtn" @click.stop="openForm('login')"
                 glossy>
                 login
          </q-btn>
          <q-btn :color="menuColor" :style="menuColor === 'white' ? 'color: black!important' : 'color: white!important'" class="text-weight-bolder" size="sm" glossy @click="launch('dashboard.php')">
            dashboard
          </q-btn>
        </div>
        <q-page-container style="padding-right:0;">
          <q-modal :content-css="{minWidth: '80vw', minHeight: '80vh'}"
                    v-model="form">
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
                  <div class="column">
                    <div class="row justify-center">
                      <q-field :error="emailError" error-label="We need a valid email" class="col-xs-12 col-md-6">
                        <q-input v-model="email" name="email" autocomplete color="green" float-label="Email" required type="email"></q-input>
                      </q-field>
                    </div>
                    <div class="row justify-center">
                      <q-field :error="passError" error-label="Password cannot be empty" class="col-xs-12 col-md-6">
                        <q-input v-model="password" name="password" autocomplete color="green" float-label="Password" required type="password"></q-input>
                      </q-field>
                    </div>
                    <div v-if="formType === 'register'" class="row justify-center">
                      <q-field :error="false" error-label="We need a valid email" class="col-xs-12 col-md-6">
                        <q-input v-model="referrer" maxlength="8" name="referrer" autocomplete color="green" float-label="Referrer code"></q-input>
                      </q-field>
                    </div>
                    <div class="q-mt-xl row justify-center">
                      <q-btn v-if="formType === 'register'" @click.stop.prevent="register()" name="submit" class="brand" type="submit">Register</q-btn>
                      <q-btn v-if="formType === 'login'" @click.stop.prevent="login('login')" name="submit" class="brand" type="submit">Login</q-btn>
                    </div>
                  </div>
                </div>
              </q-modal-layout>
              <q-inner-loading :visible="loader">
                <q-spinner-gears size="60px" color="green"></q-spinner-gears>
              </q-inner-loading>
          </q-modal>
          <div id="features" class="finebg fullscreen">
            <div class="cover row justify-center items-center content-center">
              <div class="featurecarrier row justify-center items-center">
                <q-list class="features" no-border
                style="width: 300px;">
                  <q-item :style="`padding: 0; animation-duration: ${((i+1)/2)}s;`"
                    class="featItems animated fadeInLeft" v-for="(feature, i) in features" :key="i">
                    <q-item-main class="feat row text-white text-weight-bolder"
                          :label="feature" />
                    <q-item-side right>
                      <q-item-tile class="q-display-1 text-weight-bolder" icon="mdi-check" color="green" />
                    </q-item-side>
                  </q-item>
                  </span>
                </q-list>
              </div>
            </div>
          </div>
          <div style="margin-top:100vh;" class="q-mb-md column">
            <div id="airdrop" class="row justify-center">
              <div class="animated fadeInLeft q-pa-lg col-xs-12
                  conts" style="perspective: 100px;background: green;">
                  <div class="headie absolute-center"></div>
                  <div class="headieCover absolute-center" style="z-index: -1;"></div>
                  <div class="balls1 absolute-bottom-right"></div>
                  <div class="balls2 absolute-bottom-right"></div>
                  <div class="balls3 absolute-bottom-left"></div>
                  <div class="balls4 absolute-bottom-left"></div>
                  <div class="circle1 absolute-bottom-right"></div>
                  <div class="circle1 absolute-bottom-left"></div>
                  <div class="circle2 absolute"></div>
                  <div class="circle3 absolute"></div>
                <h3>Airdrop</h3>
                <div class="text-center">
                  <p>
                    <q-btn @click="openForm('register')" class="text-bold register" label="Register"></q-btn>
                    to join the Airdrop campaign.
                  </p>
                  <p>
                    Help us spread the vibe and earn extra tokens as reward.
                  </p>
                  <p>Don't gamble with the rules.</p>
                  <p>
                    Follow all airdrop instructions honestly and responsibly.
                  </p>
                  <p>Multi accounts would be disqualified.</p>
                  <p>
                    Our hi-tech systems would fish out users who try to outsmart the system!
                  </p>
                  <p>
                      We are watching meticulously.
                  </p>
                </div>
              </div>
            </div>
            <div id="selfdrop" class="q-mt-xl row justify-around">
              <div class="animated fadeInLeft q-pa-lg col-xs-12
                  conts" style="background: #B1D3E8;">
                 <div class="headieCover absolute-center" style="z-index: -1;"></div>
                <h3>Selfdrop</h3>
                <p class="text-center">
                  We are rasing funds through a selfdrop to build the world's first semi-decentralised sportsbook. In this way we are issuing more percentage of our VIBET tokens through this selfdrop.
                </p>
                <p>
                    Token price:
                    <ul class="text-weight-bolder">
                      <li>1 VIBET equals 5gwei.</li>
                      <li>1 ETH equals 20 million VIBET.</li>
                    </ul>
                </p>
                <p>
                  Tokenmetrics:
                  <ul class="text-weight-bolder">
                    <li>Amount available for selfdrop: 10 billion (83.33%).</li>
                    <li>Circulating supply: 12 billion.</li>
                  </ul>
                </p>
                <p class="text-center">Are you excited already? Selfdrop campaign starts in</p>
                <div class="text-center q-pa-xl">
                  <h2 id="time" class="time text-light-blue-10" style="text-align: center;"></h2>
                </div>
              </div>
            </div>
            <div id="roadmap" class="relative-position row justify-center">
                <div id="roadmapNavCarrier" style="z-index: 1; display: none;" class="row absolute-top justify-center gt-lg">
                  <div style="width: fit-content;" @click="scrollRoadmapRev()">
                    <q-icon class="cursor-pointer" id="roadmapNav" name="mdi-arrow-up-drop-circle-outline" size="68px"></q-icon>
                  </div>
                </div>
              <div @scroll="checkRoadmap()" id="roadee" class="animated fadeInLeft q-pa-lg col-xs-12 conts"
                   style="background: #FFFFFF; overflow-x: hidden;">
                <h3>Roadmap</h3>
                <div class="lt-xl row justify-center" style="height:65vh;">
                  <img width="100%" height="auto" src="images/roadmap1.jpg" alt="roadmap">
                </div>
                <div class="gt-lg">
                  <div style="left: 47.5%;" class="q-mt-xl absolute-center">
                    <div style="left: -25px;" class="absolute team">
                      <div class="absolute" style="left: -31px; bottom: -21px;">
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -14px; left: 15px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 40px; height: 55px; display: inline-block; bottom: 25px; left: 24px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height:80px; border-top-right-radius: 50%; left: 12px; bottom: -3px;"></div>
                          <div class="absolute" style="z-index: 1; background: purple; width: 50px; height:5px; left: 92px; bottom: 35px;"></div>
                          <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: 129px; bottom: 30px;"></div>
                          <div class="absolute" style="background: purple; width: 5px; height:50px; left: 64px; bottom: 74px;"></div>
                          <div class="absolute smallBall" style="background: purple; width: 5px; height:5px; border-radius: 50%; left: 64px; bottom: 121px;"></div>
                          <div class="absolute semiCircle" style="background: purple; width: 70px; height: 70px; border-top-right-radius: 50%; display: inline-block; left: 25px; bottom: 8px;"></div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 70px; right: -50px;"><div>September</div>2018</div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 40px; right: -395px;">Launch bounty campaign</div>
                      </div>
                      <div class="rightTeam absolute" style="bottom: -2px; left: 6px;
                        z-index: 2; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm" name="mdi-airplane-takeoff" size="38px" color="purple-8"></q-icon></div>
                    </div>
                    <div style="z-index: 1; bottom: -123px; left: -25px;"
                          class="absolute team">
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -15px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 40px; height: 50px; display: inline-block; bottom: 25px; left: 35px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height:80px; border-top-left-radius: 50%; left: 4px; bottom: -7px;"></div>
                      <div class="absolute" style="background: purple; width: 50px; height:5px; left: -50px; bottom: 35px;"></div>
                      <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: -52px; bottom: 30px;"></div>
                      <div class="absolute" style="z-index: 1; background: purple; width: 5px; height:50px; left: 29.5px; bottom: 72px;"></div>
                      <div class="absolute smallBall" style="background: purple; width: 5px; height:5px; border-radius: 50%; left: 29px; bottom: 120px;"></div>
                      <div class="semiCircle" style="background: purple; width: 70px; height: 70px; border-top-left-radius: 50%; display: inline-block; left: -5px;"></div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 50px; left: 50px;"><div>September</div>2018</div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 40px; left: -229px;">White Paper release
                    </div>
                      <div class="leftTeam absolute" style="bottom: 15px; left: 10px; z-index: 1; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-file-document-box-outline" size="35px" color="purple-8"></q-icon></div>
                    </div>
                  </div>
                  <div style="left: 47.5%; top: 101%;" class="absolute-center">
                    <div style="left: -25px;" class="absolute team">
                      <div class="absolute" style="left: -31px; bottom: -21px;">
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -14px; left: 15px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 40px; height: 55px; display: inline-block; bottom: 25px; left: 24px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height:80px; border-top-right-radius: 50%; left: 12px; bottom: -3px;"></div>
                          <div class="absolute" style="z-index: 1; background: purple; width: 50px; height:5px; left: 92px; bottom: 35px;"></div>
                          <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: 129px; bottom: 30px;"></div>
                          <div class="absolute" style="background: purple; width: 5px; height:50px; left: 64px; bottom: 74px;"></div>
                          <div class="absolute smallBall" style="background: white; width: 5px; height:5px; border-radius: 50%; left: 64px; bottom: 121px;"></div>
                          <div class="absolute semiCircle" style="background: purple; width: 70px; height: 70px; border-top-right-radius: 50%; display: inline-block; left: 25px; bottom: 8px;"></div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 50px; left: -74px;">September 2018</div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 42px; right: -435px;">Launch of Airdrop Campaign</div>
                      </div>
                      <div class="rightTeam absolute" style="bottom: -2px; left: 6px;
                        z-index: 2; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-dropbox" size="38px" color="purple-8"></q-icon></div>
                    </div>
                    <div style="z-index: 1; bottom: -123px; left: -25px;"
                          class="absolute team">
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -15px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 40px; height: 50px; display: inline-block; bottom: 25px; left: 35px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height:80px; border-top-left-radius: 50%; left: 4px; bottom: -7px;"></div>
                      <div class="absolute" style="background: purple; width: 50px; height:5px; left: -50px; bottom: 35px;"></div>
                      <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: -52px; bottom: 30px;"></div>
                      <div class="absolute" style="z-index: 1; background: purple; width: 5px; height:50px; left: 29.5px; bottom: 72px;"></div>
                      <div class="absolute smallBall" style="background: purple; width: 5px; height:5px; border-radius: 50%; left: 29px; bottom: 120px;"></div>
                      <div class="semiCircle" style="background: purple; width: 70px; height: 70px; border-top-left-radius: 50%; display: inline-block; left: -5px;"></div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 80px; right: -200px;">30<sup>th</sup> September 2018</div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 30px; left: -250px;">Launch of Selfdrop<div>Exercise</div>
                    </div>
                      <div class="leftTeam absolute" style="bottom: 15px; left: 10px; z-index: 1; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-arrow-down-drop-circle-outline" size="35px" color="purple-8"></q-icon></div>
                    </div>
                  </div>
                  <div style="left: 47.5%; top: 143%;" class="absolute-center">
                    <div style="left: -25px;" class="absolute team">
                      <div class="absolute" style="left: -31px; bottom: -21px;">
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -14px; left: 15px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 40px; height: 55px; display: inline-block; bottom: 25px; left: 24px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height:80px; border-top-right-radius: 50%; left: 12px; bottom: -3px;"></div>
                          <div class="absolute" style="z-index: 1; background: purple; width: 50px; height:5px; left: 92px; bottom: 35px;"></div>
                          <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: 129px; bottom: 30px;"></div>
                          <div class="absolute" style="background: purple; width: 5px; height:50px; left: 64px; bottom: 74px;"></div>
                          <div class="absolute smallBall" style="background: white; width: 5px; height:5px; border-radius: 50%; left: 64px; bottom: 121px;"></div>
                          <div class="absolute semiCircle" style="background: purple; width: 70px; height: 70px; border-top-right-radius: 50%; display: inline-block; left: 25px; bottom: 8px;"></div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 50px; left: -50px;">30<sup>th</sup> October 2018</div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 42px; right: -275px;">Finish Selfdrop</div>
                      </div>
                      <div class="rightTeam absolute" style="bottom: -2px; left: 6px;
                        z-index: 2; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-check-all" size="38px" color="purple-8"></q-icon></div>
                    </div>
                    <div style="z-index: 1; bottom: -123px; left: -25px;"
                          class="absolute team">
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -15px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 40px; height: 50px; display: inline-block; bottom: 25px; left: 35px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height:80px; border-top-left-radius: 50%; left: 4px; bottom: -7px;"></div>
                      <div class="absolute" style="background: purple; width: 50px; height:5px; left: -50px; bottom: 35px;"></div>
                      <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: -52px; bottom: 30px;"></div>
                      <div class="absolute" style="z-index: 1; background: purple; width: 5px; height:50px; left: 29.5px; bottom: 72px;"></div>
                      <div class="absolute smallBall" style="background: purple; width: 5px; height:5px; border-radius: 50%; left: 29px; bottom: 120px;"></div>
                      <div class="semiCircle" style="background: purple; width: 70px; height: 70px; border-top-left-radius: 50%; display: inline-block; left: -5px;"></div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 50px; left: 47px;"><div>November</div>2018</div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 40px; left: -290px;">Lisiting on 3 Exchanges
                    </div>
                      <div class="leftTeam absolute" style="bottom: 15px; left: 10px; z-index: 1; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-stack-exchange" size="35px" color="purple-8"></q-icon></div>
                    </div>
                  </div>
                  <div style="left: 47.5%; top: 185%;" class="absolute-center">
                    <div style="left: -25px;" class="absolute team">
                      <div class="absolute" style="left: -31px; bottom: -21px;">
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -14px; left: 15px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 40px; height: 55px; display: inline-block; bottom: 25px; left: 24px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height:80px; border-top-right-radius: 50%; left: 12px; bottom: -3px;"></div>
                          <div class="absolute" style="z-index: 1; background: purple; width: 50px; height:5px; left: 92px; bottom: 35px;"></div>
                          <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: 129px; bottom: 30px;"></div>
                          <div class="absolute" style="background: purple; width: 5px; height:50px; left: 64px; bottom: 74px;"></div>
                          <div class="absolute smallBall" style="background: white; width: 5px; height:5px; border-radius: 50%; left: 64px; bottom: 121px;"></div>
                          <div class="absolute semiCircle" style="background: purple; width: 70px; height: 70px; border-top-right-radius: 50%; display: inline-block; left: 25px; bottom: 8px;"></div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 60px; left: -55px;">November 2018</div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 52px; right: -400px;">Listing on Coinmarketcap</div>
                      </div>
                      <div class="rightTeam absolute" style="bottom: -2px; left: 6px;
                        z-index: 2; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-sale" size="38px" color="purple-8"></q-icon></div>
                    </div>
                    <div style="z-index: 1; bottom: -123px; left: -25px;"
                          class="absolute team">
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -15px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 40px; height: 50px; display: inline-block; bottom: 25px; left: 35px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height:80px; border-top-left-radius: 50%; left: 4px; bottom: -7px;"></div>
                      <div class="absolute" style="background: purple; width: 50px; height:5px; left: -50px; bottom: 35px;"></div>
                      <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: -52px; bottom: 30px;"></div>
                      <div class="absolute" style="z-index: 1; background: purple; width: 5px; height:50px; left: 29.5px; bottom: 72px;"></div>
                      <div class="absolute smallBall" style="background: purple; width: 5px; height:5px; border-radius: 50%; left: 29px; bottom: 120px;"></div>
                      <div class="semiCircle" style="background: purple; width: 70px; height: 70px; border-top-left-radius: 50%; display: inline-block; left: -5px;"></div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 60px; left: 37px;">November 2018</div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 40px; left: -261px;">Listing on Trust Wallet
                    </div>
                      <div class="leftTeam absolute" style="bottom: 15px; left: 10px; z-index: 1; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-wallet" size="35px" color="purple-8"></q-icon></div>
                    </div>
                  </div>
                  <div style="left: 47.5%; top: 227%;" class="absolute-center">
                    <div style="left: -25px;" class="absolute team">
                      <div class="absolute" style="left: -31px; bottom: -21px;">
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -14px; left: 15px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 40px; height: 55px; display: inline-block; bottom: 25px; left: 24px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height:80px; border-top-right-radius: 50%; left: 12px; bottom: -3px;"></div>
                          <div class="absolute" style="z-index: 1; background: purple; width: 50px; height:5px; left: 92px; bottom: 35px;"></div>
                          <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: 129px; bottom: 30px;"></div>
                          <div class="absolute" style="background: purple; width: 5px; height:50px; left: 64px; bottom: 74px;"></div>
                          <div class="absolute smallBall" style="background: white; width: 5px; height:5px; border-radius: 50%; left: 64px; bottom: 121px;"></div>
                          <div class="absolute semiCircle" style="background: purple; width: 70px; height: 70px; border-top-right-radius: 50%; display: inline-block; left: 25px; bottom: 8px;"></div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 70px; left: -60px;">November 2018</div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 52px; right: -345px;">Website Rebranding</div>
                      </div>
                      <div class="rightTeam absolute" style="bottom: -2px; left: 6px;
                        z-index: 2; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-web" size="38px" color="purple-8"></q-icon></div>
                    </div>
                    <div style="z-index: 1; bottom: -123px; left: -25px;"
                          class="absolute team">
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -15px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 40px; height: 50px; display: inline-block; bottom: 25px; left: 35px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height:80px; border-top-left-radius: 50%; left: 4px; bottom: -7px;"></div>
                      <div class="absolute" style="background: purple; width: 50px; height:5px; left: -50px; bottom: 35px;"></div>
                      <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: -52px; bottom: 30px;"></div>
                      <div class="absolute" style="z-index: 1; background: purple; width: 5px; height:50px; left: 29.5px; bottom: 72px;"></div>
                      <div class="absolute smallBall" style="background: purple; width: 5px; height:5px; border-radius: 50%; left: 29px; bottom: 120px;"></div>
                      <div class="semiCircle" style="background: purple; width: 70px; height: 70px; border-top-left-radius: 50%; display: inline-block; left: -5px;"></div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 70px; left: 37px;"><div>December</div>2018</div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 40px; left: -178px;">Platform Live!
                    </div>
                      <div class="leftTeam absolute" style="bottom: 15px; left: 10px; z-index: 1; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-airballoon" size="35px" color="purple-8"></q-icon></div>
                    </div>
                  </div>
                  <div style="left: 47.5%; top: 269%;" class="absolute-center">
                    <div style="left: -25px;" class="absolute team">
                      <div class="absolute" style="left: -31px; bottom: -21px;">
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -14px; left: 15px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 40px; height: 55px; display: inline-block; bottom: 25px; left: 24px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height:80px; border-top-right-radius: 50%; left: 12px; bottom: -3px;"></div>
                          <div class="absolute" style="z-index: 1; background: purple; width: 50px; height:5px; left: 92px; bottom: 35px;"></div>
                          <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: 129px; bottom: 30px;"></div>
                          <div class="absolute" style="background: purple; width: 5px; height:50px; left: 64px; bottom: 74px;"></div>
                          <div class="absolute smallBall" style="background: white; width: 5px; height:5px; border-radius: 50%; left: 64px; bottom: 121px;"></div>
                          <div class="absolute semiCircle" style="background: purple; width: 70px; height: 70px; border-top-right-radius: 50%; display: inline-block; left: 25px; bottom: 8px;"></div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 50px; left: -30px;">1<sup>st</sup> Quater 2019</div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 42px; right: -455px;">Launch of Betvibe Sport book</div>
                      </div>
                      <div class="rightTeam absolute" style="bottom: -2px; left: 6px;
                        z-index: 2; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-passport" size="38px" color="purple-8"></q-icon></div>
                    </div>
                    <div style="z-index: 1; bottom: -123px; left: -25px;"
                          class="absolute team">
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -15px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 40px; height: 50px; display: inline-block; bottom: 25px; left: 35px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height:80px; border-top-left-radius: 50%; left: 4px; bottom: -7px;"></div>
                      <div class="absolute" style="background: purple; width: 50px; height:5px; left: -50px; bottom: 35px;"></div>
                      <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: -52px; bottom: 30px;"></div>
                      <div class="absolute" style="z-index: 1; background: purple; width: 5px; height:50px; left: 29.5px; bottom: 72px;"></div>
                      <div class="absolute smallBall" style="background: purple; width: 5px; height:5px; border-radius: 50%; left: 29px; bottom: 120px;"></div>
                      <div class="semiCircle" style="background: purple; width: 70px; height: 70px; border-top-left-radius: 50%; display: inline-block; left: -5px;"></div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 70px; right: -150px;">1<sup>st</sup> Quater 2019</div>
                      <div class="absolute text-bold text-italic text-center
                          content" style="z-index: 1; bottom: 40px; left: -425px;">
                        Listing on Korean Exchanges and Launching on Asian Markets
                      </div>
                      <div class="leftTeam absolute" style="bottom: 15px; left: 10px; z-index: 1; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-cart-outline" size="35px" color="purple-8"></q-icon></div>
                    </div>
                  </div>
                  <div style="left: 47.5%; top: 311%;" class="absolute-center">
                    <div style="left: -25px;" class="absolute team">
                      <div class="absolute" style="left: -31px; bottom: -21px;">
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -14px; left: 15px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 40px; height: 55px; display: inline-block; bottom: 25px; left: 24px;"></div>
                          <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height:80px; border-top-right-radius: 50%; left: 12px; bottom: -3px;"></div>
                          <div class="absolute" style="z-index: 1; background: purple; width: 50px; height:5px; left: 92px; bottom: 35px;"></div>
                          <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: 129px; bottom: 30px;"></div>
                          <div class="absolute" style="background: purple; width: 5px; height:50px; left: 64px; bottom: 74px;"></div>
                          <div class="absolute smallBall" style="background: white; width: 5px; height:5px; border-radius: 50%; left: 64px; bottom: 121px;"></div>
                          <div class="absolute semiCircle" style="background: purple; width: 70px; height: 70px; border-top-right-radius: 50%; display: inline-block; left: 25px; bottom: 8px;"></div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 50px; left: -50px;">2<sup>nd</sup> Quater 2019</div>
                          <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 52px; right: -370px;">Launch of Betvibe App</div>
                      </div>
                      <div class="rightTeam absolute" style="bottom: -2px; left: 6px;
                        z-index: 2; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-android-debug-bridge" size="38px" color="purple-8"></q-icon></div>
                    </div>
                    <div style="z-index: 1; bottom: -123px; left: -25px;"
                          class="absolute team">
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -15px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 40px; height: 50px; display: inline-block; bottom: 25px; left: 35px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height:80px; border-top-left-radius: 50%; left: 4px; bottom: -7px;"></div>
                      <div class="absolute" style="background: purple; width: 50px; height:5px; left: -50px; bottom: 35px;"></div>
                      <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: -52px; bottom: 30px;"></div>
                      <div class="absolute" style="z-index: 1; background: purple; width: 5px; height:50px; left: 29.5px; bottom: 72px;"></div>
                      <div class="absolute smallBall" style="background: purple; width: 5px; height:5px; border-radius: 50%; left: 29px; bottom: 120px;"></div>
                      <div class="semiCircle" style="background: purple; width: 70px; height: 70px; border-top-left-radius: 50%; display: inline-block; left: -5px;"></div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 70px; right: -157px;">2<sup>nd</sup> Quater 2018</div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 40px; left: -380px;">Launch of Betvibe Minning and Masternode
                    </div>
                      <div class="leftTeam absolute" style="bottom: 15px; left: 10px; z-index: 1; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-ethereum" size="35px" color="purple-8"></q-icon></div>
                    </div>
                  </div>
                  <div style="left: 47.5%; top: 353%;" class="absolute-center">
                    <div style="left: -25px;" class="absolute team">
                      <div class="absolute" style="left: -31px; bottom: -21px;">
                        <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -14px; left: 15px;"></div>
                        <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 40px; height: 55px; display: inline-block; bottom: 25px; left: 24px;"></div>
                        <div class="absolute" style="z-index: 1; background: #FFFFFF; width: 80px; height:80px; border-top-right-radius: 50%; left: 12px; bottom: -3px;"></div>
                        <div class="absolute" style="z-index: 1; background: purple; width: 50px; height:5px; left: 92px; bottom: 35px;"></div>
                        <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: 129px; bottom: 30px;"></div>
                        <div class="absolute" style="background: purple; width: 5px; height:50px; left: 64px; bottom: 74px;"></div>
                        <div class="absolute smallBall" style="background: white; width: 5px; height:5px; border-radius: 50%; left: 64px; bottom: 121px;"></div>
                        <div class="absolute semiCircle" style="background: purple; width: 70px; height: 70px; border-top-right-radius: 50%; display: inline-block; left: 25px; bottom: 8px;"></div>
                        <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 55px; left: -25px;">2<sup>nd</sup> Quater 2019</div>
                        <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 52px; right: -665px;">Worldwide gambling Campaign and Competition</div>
                    </div>
                      <div class="rightTeam absolute" style="bottom: -2px; left: 6px;
                        z-index: 2; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-dice-6" size="38px" color="purple-8"></q-icon></div>
                    </div>
                    <div style="z-index: 1; bottom: -123px; left: -25px;"
                          class="absolute team">
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height: 50px; display: inline-block; bottom: -15px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 40px; height: 50px; display: inline-block; bottom: 25px; left: 35px;"></div>
                      <div class="absolute" style="background: #FFFFFF; width: 80px; height:80px; border-top-left-radius: 50%; left: 4px; bottom: -7px;"></div>
                      <div class="absolute" style="background: purple; width: 50px; height:5px; left: -50px; bottom: 35px;"></div>
                      <div class="absolute bigBall" style="background: purple; width: 15px; height:15px; border-radius: 50%; left: -52px; bottom: 30px;"></div>
                      <div class="absolute" style="z-index: 1; background: purple; width: 5px; height:50px; left: 29.5px; bottom: 72px;"></div>
                      <div class="absolute smallBall" style="background: purple; width: 5px; height:5px; border-radius: 50%; left: 29px; bottom: 120px;"></div>
                      <div class="semiCircle" style="background: purple; width: 70px; height: 70px; border-top-left-radius: 50%; display: inline-block; left: -5px;"></div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 55px; right: -237px;">3<sup>rd</sup> Quater 2019 and Beyond</div>
                      <div class="absolute text-bold text-italic text-center content" style="z-index: 1; bottom: 40px; left: -555px;">Explosive development, Token utilization and continous platform development</div>
                      <div class="leftTeam absolute" style="bottom: 15px; left: 10px; z-index: 1; width: 50px; height: 50px; border-radius: 50%; background: white;"><q-icon class="q-ml-sm q-mt-sm" name="mdi-monitor-cellphone-star" size="35px" color="purple-8"></q-icon></div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="roadmapNavCarrier1" class="row absolute-bottom justify-center gt-lg">
                <div style="width: fit-content;" @click="scrollRoadmap()">
                  <q-icon class="cursor-pointer" id="roadmapNav1" name="mdi-arrow-down-drop-circle-outline" size="68px"></q-icon>
                </div>
              </div>
            </div>
            <div id="team" class="q-mt-xl row justify-center">
              <div class="animated fadeInLeft q-pa-lg col-xs-12
                 conts" style="background: #E8C89D;">
                 <div class="circle1 absolute-bottom-right"></div>
                 <div class="circle1 absolute-bottom-left"></div>
                 <div class="circle2 absolute"></div>
                 <div class="circle3 absolute"></div>
                <h3>Team</h3>

                <div class="row justify-center">
                  <div v-for="(teammate, i) in team" class="q-pb-xl col-xs-11 col-md-6 col-lg-4">
                    <q-card inline style="width: 90%">
                      <q-card-media overlay-position="bottom">
                        <q-card-title slot="overlay">
                          {{teammate.name}}
                          <span slot="subtitle">{{teammate.position}}</span>
                        </q-card-title>
                        <q-card-actions align="center">
                          <q-btn class="animated pulse" style="animation-iteration-count: infinite; background: #0274B3" @click="launch(teammate.link)">
                            <q-icon size="30px" name="mdi-linkedin"></q-icon>
                          </q-btn>
                        </q-card-actions>
                        <img :src="teammate.image">
                      </q-card-media>
                    </q-card>
                  </div>
                </div>
              </div>
            </div>
            <div id="bounties" class="q-mt-xl row justify-center">
              <div class="col-xs-12 animated fadeInLeft q-pa-lg conts"
                   style="background: #D2FFB8;">
                 <div style="background: orangered" class="balls1 absolute-bottom-right"></div>
                 <div style="background: orangered" class="balls2 absolute-bottom-right"></div>
                 <div style="background: orangered" class="balls3 absolute-bottom-left"></div>
                 <div style="background: orangered" class="balls4 absolute-bottom-left"></div>
                 <div class="circle1 absolute-bottom-right"></div>
                 <div class="circle1 absolute-bottom-left"></div>
                 <div class="circle2 absolute"></div>
                 <div class="circle3 absolute"></div>
              <h3>Bounties</h3>

              <div class="text-center q-display-4" style="color: #FF88DA; text-shadow:  2px 2px 10px orangered, -2px -2px 10px orangered;">Bounty Live!</div>
              <p class="text-center">
              Click this link to access our <q-btn @click="launch('https://bitcointalk.org/index.php?topic=5032559.msg45893532#msg45893532')" class="text-weight-bolder whitepaper">Bounty program on Bitcointalk</q-btn>
              </p>
              <p class="text-center q-pb-xl q-mb-xl">
                Ensure all rules are followed to the later. Don't gamble  with the rules.
              </p>
              </div>
            </div>
          </div>
          <div style="margin-top: 10pc; margin-bottom: 3pc;" class="pie row
               justify-center relative-position">
            <div class="col-10 absolute-center">
              <img width="100%" height="auto" src="images/fund1.png" alt="allocation">
            </div>
          </div>
          <!-- <div class="relative-position mediaDiv q-pa-lg"
               style="background: #92B23C">
            <h3>Media</h3>
            <div class="row justify-center">
              <div class="q-pb-xl col-xs-11 col-md-6 col-lg-4">
                <q-card inline style="width: 90%">
                  <q-card-media overlay-position="top">
                    <q-card-title slot="overlay">Airdrop Listing</q-card-title>
                    <img width="100%" height="auto" src="images/media1.jpeg" alt="">
                    <q-card-actions align="center">
                      <q-btn class="animated pulse" @click="launch('https://twitter.com/AirdropDet')"
                            style="animation-iteration-count: infinite; background: #1DA1F2">
                        <q-icon color="white" size="30px" name="mdi-twitter"></q-icon>
                      </q-btn>
                      <q-btn class="animated pulse"
                             @click="launch('http://t.me/AirdropDetective')"
                            style="animation-iteration-count: infinite; background: #31A9DD">
                        <q-icon color="white" size="30px" name="mdi-telegram"></q-icon>
                      </q-btn>
                    </q-card-actions>
                  </q-card-media>
                </q-card>
              </div>
            </div>
          </div> -->
        </q-page-container>
        <q-layout-footer style="bottom: -200px; z-index: 1;" class="row q-pa-lg
                         justify-around">
          <div class="col-xs-12 col-md-6 col-lg-4 q-pa-lg">
            <h6 class="text-center">Join the Conversation</h6>
            <div class="row justify-around">
            <div @click="launch(' https://bitcointalk.org/index.php?topic=5032557.msg45893473#msg45893473')" class="media-logo"
                 style="background: #FFB00C;">
              <q-icon name="mdi-bitcoin" color="white" size="40px"></q-icon>
            </div>
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
      let navItemse = document.getElementsByClassName('navItems')
      let featItems = document.getElementsByClassName('featItems')
          document.onreadystatechange = () => {
            if (document.readyState === 'complete') {
              setTimeout(() => {
                for (let i = 0; i < navItemse.length; i++) {
                  const element = navItemse[i];
                  element.classList.remove('animated')
                }
                document.getElementsByClassName('whitepaper')[0].classList.remove('animated')
                document.getElementsByClassName('whitepaper')[1].classList.remove('animated')
              }, 1500);
              setTimeout(() => {
                for (let i = 0; i < featItems.length; i++) {
                  const element = featItems[i];
                  element.classList.remove('fadeInLeft')
                  element.style.animationDelay = `${i*2}s`
                  element.style.animationIterationCount = 'infinite'
                  element.classList.add('pulse')
                }
              }, 2000);
          }
          }
      let navitems = document.getElementsByClassName('q-item-label')
      var betvibe = new Vue({
        el: '#q-app',
        data: function () {
          return {
            loader: false,
            opened: false,
            modal: false,
            form: false,
            formType: '',
            senderName: '',
            message: '',
            email: '',
            password: '',
            referrer: '',
            emailError: false,
            passError: false,
            drawerState: true,
            navColor: 'transparent',
            menuColor: 'white',
            navItems: [
              {name: 'AIRDROP', link: 'airdrop'},
              {name: 'SELFDROP', link: 'selfdrop'},
              {name: 'ROADMAP', link: 'roadmap'},
              {name: 'TEAM', link: 'team'},
              {name: 'BOUNTIES', link: 'bounties'},
              {name: 'FEATURES', link: 'features'},
            ],
            features: [
              'Fast payout',
              'Completely Anonymous',
              'high Odds',
              'No Kyc/Aml',
              'Worldwide',
              'Betvibe Masternodes'
            ],
            team: [
              {
                name: 'Les Squires',
                position: 'Founder and CTO',
                image: 'images/found1.jpeg',
                link: 'https://www.linkedin.com/in/les-squires-691308163/'
              },
              {
                name: 'Grey Michael',
                position: 'Co-Founder, CMO',
                image: 'images/found2.jpeg',
                link: 'https://www.linkedin.com/in/grey-michael-ab005997'
              },
              {
                name: 'Harold Santos',
                position: 'Co-Founder, Business and Brand Manager',
                image: 'images/found3.jpeg',
                link: 'https://www.linkedin.com/in/harold-santos-576a5395'
              },
              {
                name: 'Audrey Becker',
                position: 'Director of Community Relations',
                image: 'images/found4.jpeg',
                link: 'https://www.linkedin.com/in/audrey-becker-bertens-b9159420'
              }
            ],
            faqs: []
          }
        },
        methods: {
          openForm (arg) {
            this.formType = arg
            this.form = true
          },
          login (arg) {
            this.loader = true
            let note = this.$q.notify
            let email = this.email
            let password= this.password
            $.ajax({
              type: 'POST',
              url: 'scripts/login.php',
              data: {
                email: email,
                password: password,
                action: arg
              },
              cache: false,
              success: function(response) {
                note({
                  message: response,
                  timeout: 500,
                  type: /Success/.test(response) || /Admin/.test(response) ? 'positive' : 'negative',
                  position: 'top'
                })   
                if (/Success/.test(response) && !/Admin/.test(response)) {
                  setTimeout(() => {
                    betvibe.loader = false
                    window.open(`dashboard.php?email=${betvibe.email}`)
                    betvibe.form = false
                  }, 1000)
                } else if (/Admin/.test(response)) {
                  setTimeout(() => {
                    betvibe.loader = false
                    window.open(`admin.php?email=${betvibe.email}`)
                    betvibe.form = false
                  }, 1000)
                } else {
                  betvibe.loader = false
                }
              }
            })
          },
          register () {
            this.emailError = false
            this.passError = false
            if (/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(this.email) && this.password !== '') {
              this.loader = true
              let data = 'email=' + this.email + '&password=' + this.password + '&referrerCode=' + this.referrer
              let note = this.$q.notify
              $.ajax({
                type: 'POST',
                url: 'scripts/register.php',
                data: data,
                cache: false,
                success: function(response) {
                  note({
                    message: response,
                    timeout: 500,
                    type: /Success/.test(response) ? 'positive' : 'negative',
                    position: 'top'
                  })
                  if (/Success/.test(response)) {
                    setTimeout(() => {
                      window.open(`dashboard.php?email=${betvibe.email}`)
                      betvibe.loader = false
                      betvibe.form = false
                    }, 1000);
                  } else {
                    betvibe.loader = false
                  }
                }
              })
            } else if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(this.email)) {
              this.emailError = true
            } else if(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(this.email)) {
              this.emailError = false
              if(this.password === '') {
                this.passError = true
              } else if(this.password !== '') {
                this.passError = false
              }
            }
          },
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
          goTo (link) {
            this.drawerState = false
            let el = document.getElementById(link)
            let target = Quasar.utils.scroll.getScrollTarget(el)
            let offset = el.offsetTop - 140
            Quasar.utils.scroll.setScrollPosition(target, offset, 700)
          },
          launch (url) {
            Quasar.utils.openURL(url)
          },
          scrollRoadmap () {
            document.getElementById('roadee').scrollBy(0, 400)
          },
          scrollRoadmapRev () {
            document.getElementById('roadee').scrollBy(0, -400)
          },
          checkRoadmap() {
            if (document.getElementById('roadee').scrollTop > 0 && document.getElementById('roadee').scrollTop < 1403) {
              document.getElementById('roadmapNavCarrier').style.display = 'flex'
            } else if (document.getElementById('roadee').scrollTop === 0) {
              document.getElementById('roadmapNavCarrier').style.display = 'none'
              document.getElementById('roadmapNavCarrier1').style.display = 'flex'
            } else if (document.getElementById('roadee').scrollTop === 1403) {
              document.getElementById('roadmapNavCarrier1').style.display = 'none'
            } else if (document.getElementById('roadee').scrollTop < 1403) {
              document.getElementById('roadmapNavCarrier1').style.display = 'flex'
            }
          },
          reactScroll: function () {
            if (window.scrollY >= 66) {
              this.navColor = 'white'
              this.menuColor = 'black'
              document.getElementById('logo').src = 'images/logo-white.jpg'
              document.getElementById('subtitle').classList.add('text-black')
              document.getElementsByTagName('header')[0].classList.add('q-layout-header')
              for (let i = 2; i < 8; i++) {
                const element = navitems[i];
                element.classList.add('text-black')
                element.classList.remove('text-white')
              }
            } else if (window.scrollY <= 66) {
              document.getElementById('logo').src = 'images/logo-dark.jpg'
              this.navColor = 'transparent'
              this.menuColor = 'white'
              document.getElementById('subtitle').classList.remove('text-black')
              document.getElementById('subtitle').classList.add('text-white')
              document.getElementsByTagName('header')[0].classList.remove('q-layout-header')
              for (let i = 2; i < 8; i++) {
                const element = navitems[i];
                element.classList.remove('text-black')
                element.classList.add('text-white')
              }
            }
          }
        },
        mounted() {
        },
      })
    </script>
  </body>
</html>
