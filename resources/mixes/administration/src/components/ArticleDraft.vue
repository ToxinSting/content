<script>
  import Core from '../main'
  export default {
    beforeRouteEnter (to, from, next) {
      Core.instance.store.commit('progress', 'start')
      Core.http.post(window.api + '/article/draft/fetch').then(function (response) {
        Core.instance.store.commit('progress', 'done')
        next((vm) => {
          vm.list = []
          response.data.data.forEach((article) => {
            article.checked = false
            vm.list.push(article)
          })
          vm.pagination = response.data.pagination
        })
      }).catch(() => {
        Core.instance.store.commit('progress', 'fail')
      })
    },
    components () {
      return {
        Paginator: Core.instance.components.paginator
      }
    },
    data () {
      return {
        list: [],
        pagination: {
          last_page: 1
        }
      }
    },
    methods: {
      check: function (article) {
        article.checked = !article.checked
      },
      checkAll: function () {
        this.list.forEach((article) => {
          article.checked = true
        })
      },
      checkNone: function () {
        this.list.forEach((draft) => {
          draft.checked = !draft.checked
        })
      },
      paginator: function (page) {
        let _this = this
        _this.$http.post(window.api + '/article/draft/fetch?page=' + page).then(function (response) {
          _this.list = []
          response.data.data.map(function (draft) {
            draft.checked = false
            _this.list.push(draft)
          })
          _this.pagination = response.data.pagination
        })
      },
      remove: function (id) {
        let _this = this
        _this.$http.post(window.api + '/article/draft/delete', {
          id: id
        }).then(function (response) {
          _this.list = []
          response.data.data.map(function (draft) {
            draft.checked = false
            _this.list.push(draft)
          })
          _this.pagination = response.data.pagination
          _this.$store.commit('message', {
            show: true,
            type: 'notice',
            text: response.data.message
          })
        })
      },
      removeSelected: function () {
        let _this = this
        _this.list.forEach((draft) => {
          if (draft.checked) {
            _this.$http.post(window.api + '/article/draft/delete', {
              id: draft.id
            }).then(function (response) {
              _this.list = []
              response.data.data.map(function (article) {
                article.checked = false
                _this.list.push(article)
              })
              _this.pagination = response.data.pagination
              _this.$store.commit('message', {
                show: true,
                type: 'notice',
                text: '批量删除成功！'
              })
            })
          }
        })
      },
      submit: function (e) {
        let _this = this

        _this.$validator.validateAll()

        if (_this.errors.any()) {
          return false
        }
      }
    },
    mounted () {
      this.$store.commit('title', '草稿箱 - 文章 - Notadd Administration')
    }
  }
</script>
<style scoped>
    .box {
        border-top: none;
        padding-left: 40px;
        padding-right: 40px;
    }

    .box-body {
        border-bottom: 1px solid #eeeeee;
        border-radius: 0;
        border-top: 1px solid #eeeeee;
    }

    .box-body > .table > tbody > tr > td {
        border-color: #eeeeee;
        height: 60px;
        vertical-align: middle;
    }

    .box-body > .table > tbody > tr > td:last-child {
        letter-spacing: 6px;
        text-align: right;
    }

    .box-body > .table > tbody > tr > td:last-child > .btn {
        border-radius: 4px;
        letter-spacing: 0;
        padding: 5px 12px;
    }

    .box-footer {
        text-align: right;
    }

    .box-footer,
    .box-header {
        height: 80px;
        padding: 22px 0;
        position: relative;
    }

    .box-header > .box-search {
        float: left;
        width: 320px;
    }

    .box-header > .box-search > .form-control {
        border-bottom-left-radius: 18px;
        border-color: #cccccc;
        border-right-width: 0;
        border-top-left-radius: 18px;
    }

    .box-header > .box-search > .input-group-btn {
        border: 1px solid #cccccc;
        border-bottom-right-radius: 18px;
        border-left-width: 0;
        border-top-right-radius: 18px;
        overflow: hidden;
    }

    .box-header > .box-search > .input-group-btn > .btn {
        padding-bottom: 6px;
        padding-top: 6px;
        width: 60px;
    }

    .box-header > .box-extend {
        letter-spacing: 16px;
        text-align: right;
    }

    .box-header > .box-extend > .btn {
        border-radius: 6px;
        padding-left: 32px;
        padding-right: 32px;
    }

    .box-body > .table > tbody > tr > td:first-child {
        background: url("../../static/images/unselected.svg") 10px center no-repeat;
        padding-left: 50px;
    }

    .box-body > .table > tbody > tr > td:first-child.checked {
        background: url("../../static/images/selected.svg") 10px center no-repeat;
    }

    .box-body > .table > tbody > tr > td:last-child > .btn,
    .box-header > .box-extend > .btn {
        background: #ffffff;
        border-width: 1px;
        color: #000000;
    }

    .box-body > .table > tbody > tr > td:last-child > .btn-primary,
    .box-header > .box-extend > .btn-primary {
        border-color: #3498db;
        color: #3498db;
    }

    .box-body > .table > tbody > tr > td:last-child > .btn-primary:hover,
    .box-header > .box-extend > .btn-primary:hover {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff;
    }

    .box-body > .table > tbody > tr > td:last-child > .btn-info,
    .box-header > .box-extend > .btn-info {
        border-color: #8ba0ca;
        color: #8ba0ca;
    }

    .box-body > .table > tbody > tr > td:last-child > .btn-info:hover,
    .box-header > .box-extend > .btn-info:hover {
        background-color: #8ba0ca;
        border-color: #8ba0ca;
        color: #ffffff;
    }

    .box-body > .table > tbody > tr > td:last-child > .btn-danger,
    .box-header > .box-extend > .btn-danger {
        border-color: #ef5151;
        color: #ef5151;
    }

    .box-body > .table > tbody > tr > td:last-child > .btn-danger:hover,
    .box-header > .box-extend > .btn-danger:hover {
        background-color: #ef5151;
        border-color: #ef5151;
        color: #ffffff;
    }

    .box-header > .box-extend > .btn {
        letter-spacing: 0;
    }

    .box-header > .box-extend > .btn-create {
        background: #3498db;
        color: #ffffff;
        letter-spacing: 0;
    }

    .box-header > .box-extend > .btn-create:hover {
        background: #258cd1;
    }

    .box-header > .box-extend > .btn-create:active,
    .box-header > .box-extend > .btn-create:focus {
        background: #2b7cb3;
    }
</style>
<template>
    <div class="box">
        <div class="box-header">
            <div class="box-extend">
                <button class="btn btn-primary" @click="checkAll">全选</button>
                <button class="btn btn-primary" @click="checkNone">反选</button>
                <button class="btn btn-danger" @click="removeSelected">删除</button>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <colgroup>
                    <col class="col-md-8">
                    <col class="col-md-2">
                    <col class="col-md-2">
                </colgroup>
                <tbody>
                <tr v-for="draft in list">
                    <td :class="{ checked: draft.checked }" @click="check(draft)">{{ draft.title }}</td>
                    <td>{{ draft.date }}</td>
                    <td>
                        <router-link :to="'/content/article/' + draft.id + '/draft'" class="btn btn-info btn-sm">编辑
                        </router-link>
                        <button class="btn btn-danger btn-sm" @click="remove(draft.id)">删除</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            <paginator :pageCount="pagination.last_page"
                       :pageRange="3"
                       :marginPages="2"
                       :clickHandler="paginator"
                       prevText="上一页"
                       nextText="下一页"
                       containerClass="pagination no-margin"
                       pageClass="'page-item'">
            </paginator>
        </div>
    </div>
</template>