// pages/me/index.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    userInfo: {
      nick_name: "迪士尼在逃唐老鸭",
      user_headimg: "/images/login/pkq.jpeg",
      school_name: "电子科技大学成都学院",
      release: 0,
      saled: 0,

    }
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.showLoading({
      title: '加载中',
    });
    var app = getApp()
    var that = this
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/personal/getUserInfo',
      method: 'POST',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      data: {
        thr_session: app.globalData.userSessionKey
      },
      success: function(res){
        // console.log(res.data.data)
        that.setData({
          userInfo: res.data.data,
        })
        wx.hideLoading()
      }
    })
  },

  // 点击修改地址信息
  goUpdateAddress: function(){
    wx.navigateTo({
      url: '/pages/me/trade/consignee/consignee',
    })
  },

  // 点击充值
  goRecharge: function(){
    wx.navigateTo({
      url: '/pages/me/trade/recharge/recharge',
    })
  },

  // 打开软件使用许可协议
  toUseProtrol: function(){
    wx.navigateTo({
      url: '/pages/me/protrol/use/use'
    })
  },

  // 跳转去商品列表
  goGoodsList: function(e){
    var status = e.currentTarget.dataset.status
    wx.navigateTo({
      url: '/pages/me/goods/goodslist?status=' + status
    })
  },

  // 跳转去订单列表
  goTradeList: function(e){
    var status = e.currentTarget.dataset.status
    wx.navigateTo({
      url: '/pages/me/trade/tradelist/tradelist?status=' + status
    })
  },
})