// pages/me/trade/tradedetail/tradedetail.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    tradeInfo: [],
    canPay: 'disabled',
    canCancel: 'disabled',
    canFinish: 'disabled',


    clickAffirm: true, // 禁用点击"确认订单"
    showDialog: false , // 弹窗

    showPayPwdInput: false,  //是否展示密码输入层
    pwdVal: '',  //输入的密码
    payFocus: false, //文本框焦点

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // console.log(options.id)
    var that = this

    wx.showLoading({
      title: '加载中'
    })
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/trade/getTradeDetail',
      method: 'POST',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      data: {
        trade_id: options.id,
      },
      success: function(res){
        // console.log(res.data.data)
        that.setData({
          tradeInfo: res.data.data
        })

        if(res.data.data.trade_status == 0){
          that.setData({
            canPay: '',
            canCancel: '',
          })
        }

        if(res.data.data.trade_status == 3){
          that.setData({
            canPay: 'disabled',
            canCancel: 'disabled',
          })
        }

        if(res.data.data.trade_status == 1){
          that.setData({
            canFinish: ''
          })
        }

        wx.hideLoading()
      }
    })

  },

  // 点击支付
  toPay: function(){
    var that = this
    console.log("点击支付")
    that.setData({
      clickAffirm: true,
      showDialog: true,
      showPayPwdInput: true,
    })
  },

  // 点击取消订单
  toCancel: function(){
    var that = this
    wx.showModal({
      title: '提示', 
      content: '您确定要取消该订单吗？', 
      success: function (res) { 
        if (res.confirm) {//这里是点击了确定以后
          //  console.log('用户点击确定')
           that.cancelTrade(that.data.tradeInfo.id,that)
         } else {//这里是点击了取消以后
          //  console.log('用户点击取消')
         }
 
      }
 
    })
  },

  // 发起取消订单请求
  cancelTrade: function(id,that){
    var app = getApp()
    var options = {
      id: id
    }
    console.log(app.globalData.userSessionKey)
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/trade/cancelTrade',
      method: 'POST',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      data: {
        trade_id: id,
        thr_session: app.globalData.userSessionKey,
      },
      success: function(res){
        wx.showToast({
          title: res.data.msg || '无操作',
          icon: 'none'
        })
        that.onLoad(options)
      }
    })
  },

   /**
   * 校验输入的密码
   */
  finish: function(){
    var val = this.data.pwdVal;
    var app = getApp()
    var that = this

    if(val == ''){
      wx.showToast({
        title: '请输入密码',
        icon: 'none'
      })
    }else{
      wx.request({
        url: 'https://www.taoxiaobei.cn/wx/goods/checkPassword',
        data: {
          thr_session: app.globalData.userSessionKey,
          password: val,
          trade_id: that.data.tradeInfo.id,
        },
        method: 'POST',
        header: {
          'content-type': 'application/json'
        },
        success: function (res) {
          //console.log(res)
          if(res.data.code == 500){
            wx.showToast({
              title: res.data.msg,
              icon: 'none',
              duration: 3000
            })

            wx.navigateTo({
              url: '/pages/me/trade/tradelist/tradelist?status=0'
            })
          }
            
          if(res.data.code == 200){
            wx.showToast({
              title: res.data.msg,
              duration: 3000
            })
            wx.navigateTo({
              url: '/pages/me/trade/tradelist/tradelist?status=1'
            })
          }
            
        }
      })
      // 隐藏输入密码面板，清除输入内容
      this.setData({ 
                     showPayPwdInput: false, 
                     payFocus: false, 
                     showDialog: !this.data.showDialog,
                     clickAffirm: !this.data.clickAffirm,
                     pwdVal: ''});
    }
  },

  /**
  * 控制 pop 的打开关闭
  * 该方法作用有2:
  * 1：点击弹窗以外的位置可消失弹窗
  * 2：用到弹出或者关闭弹窗的业务逻辑时都可调用
  */
 toggleDialog() {
  this.setData({
    showDialog: !this.data.showDialog,
    clickAffirm: !this.data.clickAffirm,
    showPayPwdInput: !this.data.showPayPwdInput, 
    payFocus: !this.data.payFocus
  })
},
  /**
   * 获取输入框焦点
   */
  getFocus: function(){
    this.setData({ payFocus: true });
  },
  /**
   * 输入密码监听
   */
  inputPwd: function(e){
    this.setData({ pwdVal: e.detail.value });
  },

  // 点击收货
  tofinishTrade: function(){
    var that = this 
    var app = getApp()
    wx.showModal({
      title: '提示', 
      content: '您确定已收到货物吗？点击确定将关闭本次交易。', 
      success: function (res) { 
        if (res.confirm) {//这里是点击了确定以后
           wx.request({
            url: 'https://www.taoxiaobei.cn/wx/trade/finishTrade',
            method: 'POST',
            header: {
              "content-type": "application/x-www-form-urlencoded"
            },
            data: {
              trade_id: that.data.tradeInfo.id,
              thr_session: app.globalData.userSessionKey,
            },
            success: function(res){
              wx.showToast({
                title: res.data.msg || '无操作',
                icon: 'none'
              })
              wx.navigateTo({
                url: '/pages/me/trade/tradelist/tradelist?status=2'
              })
            }
           })
         } else {//这里是点击了取消以后
          //  console.log('用户点击取消')
         }
 
      }
 
    })
  }

})