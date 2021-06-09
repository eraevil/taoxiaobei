// pages/me/trade/recharge/recharge.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    userInfo:{
      user_headimg: '/images/login/pkq.jpeg',
      nick_name: '迪士尼在逃唐老鸭',
      money: '3000000.00',
      user_num: 'TXB20220812xxxxxxx'// 用户编号
    },
    admin_qr: '/images/wechat.png', // 管理员二维码
    admin_name: 'ZNHFIVE0325', // 管理员微信号
    recharge: [], //充值记录
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // 加载中
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
        res.data.data.money = that.toDecimal2(res.data.data.money)
        that.setData({
          userInfo: res.data.data,
        })
      }
    })

    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/personal/getRechargeInfo',
      method: 'POST',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      data: {
        thr_session: app.globalData.userSessionKey
      },
      success: function(res){
        that.setData({
          recharge: res.data.data,
        })
        wx.hideLoading()
      }
    })

    wx.hideLoading()

  },

  // 转化为两位小数
  toDecimal2: function (x) {
    var f = parseFloat(x);
    if (isNaN(f)) {
      return false;
    }
    var f = Math.round(x*100)/100;
    var s = f.toString(); 
    var rs = s.indexOf('.');
    if (rs < 0) {
      rs = s.length;
      s += '.';
    }
    while (s.length <= rs + 2) {
      s += '0';
    }
    return s;
  },

  // 预览图片
  preview(event) {
    console.log(event.currentTarget.dataset.src)
    let currentUrl = event.currentTarget.dataset.src
    var img = []
    img[0] = this.data.admin_qr
    wx.previewImage({
      current: currentUrl, // 当前显示图片的http链接
      urls: img// 需要预览的图片http链接列表
    })
  },

  // 复制文本
  copyText() {
    wx.setClipboardData({
      data: this.data.userInfo.user_num,
      success: function(res) {
        wx.getClipboardData({
          success: function(res) {
            wx.showToast({
              title: '复制成功',
              icon: 'none',
              duration: 2000
            })
          }
        })
      }
    })
  }
})