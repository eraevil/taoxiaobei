// pages/home/purchase/affirm.js
//获取应用实例
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    goods_id: '',
    clickAffirm: true, // 禁用点击"确认订单"
    showDialog: false , // 弹窗

    showPayPwdInput: false,  //是否展示密码输入层
    pwdVal: '',  //输入的密码
    payFocus: false, //文本框焦点

    productInfo: [], // 商品信息
    consignee_status: 0,
    consignee_name: '',
    consignee_phone: '',
    consignee_address: '',
    consignee_remark: '',

    trade_id: '', // 订单id
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
    wx.navigateTo({
      url: '/pages/me/trade/tradelist/tradelist?status=0'
    })
  },

  // 点击"确认订单"按钮 
  ok: function(){
    var app = getApp()
    var data = this.__data__
    var that = this
    // console.log(data.goods_id)
    // 创建订单
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/goods/createOrder',
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      data: {
        goods_id: data.goods_id,
        thr_session: app.globalData.userSessionKey, // session值用以换取用户id信息
        consignee_name: data.consignee_name,
        consignee_phone: data.consignee_phone,
        consignee_address: data.consignee_address,
        consignee_remark: data.consignee_remark,
        trade_money: data.productInfo.price
      },
      success: function (res) {
        // console.log(res)
        if(res.data.code == 200){
          // 显示支付
          if(data.showDialog == false){
            that.setData({
              clickAffirm: true,
              showDialog: true,
              showPayPwdInput: true, 
              trade_id: res.data.data, // 订单编号
            })
          }else{
            wx.showToast({
              title: res.data.msg,
              icon: 'none'
            })
          }
        }
        
      }
    })
  },
  onLoad: function (options) {
    // 加载商品信息
    var that = this
    var app = getApp()
    this.setData({
      goods_id: options.id
    })
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/goods/getGoodsInfo',
      data: {
        goods_id: this.data.goods_id,
        thr_session: app.globalData.userSessionKey
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        res.data.data.price = res.data.data.price.toFixed(2);
        that.setData({
          productInfo: res.data.data
        })
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
          trade_id: that.data.trade_id,
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

  // 去维护收货信息
  toConsignee: function(){
    wx.navigateTo({
      url: '/pages/me/trade/consignee/consignee',
    })
  },

  onShow: function () {
    // console.log("刷新")
    var app = getApp()
    var that = this
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/personal/getConsigneeInfo',
      method: 'POST',
      data: {
        thr_session: app.globalData.userSessionKey
      },
      success: function(res){
        // 获取收货人信息，并赋给本地
        that.setData({
          consignee_name: res.data.data.consignee_name,
          consignee_phone: res.data.data.consignee_phone,
          consignee_address: res.data.data.consignee_address,
          consignee_remark: res.data.data.consignee_remark,
          consignee_status: res.data.data.consignee_status,
          clickAffirm: false, // 禁用点击"确认订单"
          showDialog: false , // 弹窗
          showPayPwdInput: false,  //是否展示密码输入层
          pwdVal: '',  //输入的密码
          payFocus: false, //文本框焦点
        })

        if(res.data.data.consignee_status == 0){
          that.setData({
            clickAffirm: true, // 禁用点击"确认订单"
          })
        }
      }
    })
  },
})