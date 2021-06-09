// pages/me/trade/tradelist/tradelist.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    trade_list: [{
      img: "/images/cover.png",
      goods_title: "",
      price: "",
      add_time: "",
      flag_image: "",
    }]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    if(options.status == null)options.status = 0
    var that = this
    // 加载中
    wx.showLoading({
      title: '加载中',
      
    });
    var app = getApp()

    // console.log(options.status)
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/trade/getTradeListByStatus',
      method: 'POST',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      data: {
        thr_session: app.globalData.userSessionKey,
        status: options.status,
      },
      success: function(res){
        console.log(res.data.data)
        that.setData({
          trade_list: res.data.data
        })

        // 无数据提示
        if(that.data.trade_list.length == 0){
          wx.hideLoading()
          wx.showToast({
            title: '无数据',
            icon: 'none',
            duration: 3000
          })
          setTimeout(function () {
            wx.navigateBack({
              delta: -1,
            }) 
           }, 3000) //延迟时间 这里是1秒

        }
        var img_url = ''
        for(var i = 0; i < that.data.trade_list.length;i++){
          switch(that.data.trade_list[i].trade_status){
            case 0: img_url = "/images/waitpay.png";break;
            case 1: img_url = "/images/paied.png";break;
            case 2: img_url = "/images/oktrade.png";break;
            case 3: img_url = "/images/cancel.png";break;
          }
          var elem = 'trade_list[' + i + '].flag_image'
          that.setData({
            [elem]: img_url
          })
        }
        wx.hideLoading()
      }
    })
    wx.hideLoading()
  },

  goDetails: function(e){
    var id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/me/trade/tradedetail/tradedetail?id=' + id
    })
  }
})