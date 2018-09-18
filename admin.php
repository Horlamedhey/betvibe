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
                   cursor-pointer brand text-weight-bold">Welcome Admin!</div>
            </q-toolbar-title>
          </div>
        </q-toolbar>
      </q-layout-header>
      <q-page-sticky style="z-index: 1" position="bottom-right" :offset="[18, 158]">
        <q-btn round color="green" @click="window.location.href = 'scripts/logout.php'" icon="mdi-logout">
          <q-tooltip>Logout</q-tooltip>
        </q-btn>
      </q-page-sticky>
      <q-page-container class="q-mb-xl">
        <q-table title="USERS DATA" :data="tableData" :columns="columns"
                 row-key="user" color="positive" :loading="loader"
                 :pagination.sync="paginationControl">
          <div slot="pagination" slot-scope="props" class="row flex-center
               q-pa-sm">
               <div>
                <q-btn round dense size="sm" icon="mdi-undo" color="secondary"
                  class="" :disable="props.isFirstPage"
                  @click="props.prevPage"/>
               </div>
            <div class="text-black q-pa-sm" style="font-size: small">Page {{ props.pagination.page }} / {{ props.pagesNumber }}</div>
            <div>
              <q-btn round dense size="sm" icon="mdi-redo" color="secondary"
                  :disable="props.isLastPage" @click="props.nextPage"/>
            </div>
          </div>
        </q-table>
        <div class="q-mt-xl row justify-center">
          <q-btn @click="generate()" color="green">Generate Data</q-btn>
        </div>
        <div v-if="jsonData" class="relative-position q-mt-xl q-pa-lg bg-grey row justify-center">
          <q-btn @click="jsonData = false" class="absolute-top-right">
            <q-icon name="mdi-close"></q-icon>
          </q-btn>
          <textarea style="letter-spacing:2px; line-height: 2" class="text-center row text-white q-ma-sm bg-black" id="jsonData"></textarea>
          <q-btn @click="copy()" color="green">Copy</q-btn>
        </div>
      </q-page-container>

      <q-layout-footer style="bottom: -200px;" class="row q-pa-lg
                          justify-around">
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
            jsonData: false,
            tableData: [],
            columns: [
              {
                name: 'email',
                required: true,
                label: 'EMAIL',
                align: 'left',
                field: 'email',
                sortable: true,
                classes: 'my-class',
                style: 'width: 500px'
              },
              {
                name: 'ethereum',
                required: true,
                label: 'ETH ADDRESS',
                align: 'left',
                field: 'ethereum',
                sortable: true,
                classes: 'my-class',
                style: 'width: 500px'
              },
              {
                name: 'tokens',
                required: true,
                label: 'TOKENS',
                align: 'left',
                field: 'tokens',
                sortable: true,
                classes: 'my-class',
                style: 'width: 500px'
              }
            ],
            paginationControl: { rowsPerPage: 20, page: 1 },
            modal: false,
            loader: true,
          }
        },
        methods: {
          generate () {
            let a = []
            betvibe.tableData.forEach(v => {
              let b = {}
              b[JSON.stringify(v.ethereum)] = v.tokens.toString()
              a.push(b)
            })
            this.jsonData = true
            setTimeout(() => {
            document.getElementById('jsonData').value = JSON.stringify(a).replace(/\\\"/g, "")
            }, 100);
          },
          copy () {
            document.getElementById('jsonData').select()
            // document.getElementById('jsonData').un
            document.execCommand('copy')
          },
          launch (url) {
            Quasar.utils.openURL(url)
          }
        },
        watch: {
          'paginationControl.page' (page) {
            this.$q.notify({
              color: 'secondary',
              message: `Navigated to page ${page}`,
              actions: page < 4
                ? [{
                  label: 'Go to last page',
                  handler: () => {
                    this.paginationControl.page = 4
                  }
                }]
                : null
            })
          }
        },
        mounted() {
          document.oncopy = function(){
            betvibe.$q.notify({
              message: 'Copied',
              timeout: 1000,
              position: 'top',
              type: 'positive'
            })
          }
        },
      })
    </script>
  <script type="text/javascript">
    let note = betvibe.$q.notify
    $.get('scripts/users.php', {action: 'admin'}, (data, status) => {
      if (status === 'timeout' || status === 'error') {
        note({
          message: status + ', please try again',
          timeout: 0,
          type: /Success/.test(response) ? 'positive' : 'negative',
          position: 'top',
          actions: [
            {
              label: 'Retry',
              handler: () => {
                fetch()
              }
            }
          ]
        })
      } else if (status === 'success') {
        new Promise((resolve, reject) => {
          let users = JSON.parse(data)
          users.forEach(v => {
            v.tokens = parseInt(v.tokens) + (parseInt(v.referrals) * 5000)
          })
          betvibe.tableData = users
          resolve(betvibe.tableData)
        }).then(() => {
          betvibe.loader = false
        })
      }
    })
  </script>
</body>
</html>