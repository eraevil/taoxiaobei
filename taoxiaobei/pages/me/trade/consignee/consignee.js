// pages/me/trade/consignee/consignee.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    consignee_name: '',
    consignee_phone: '',
    consignee_address: '',
    consignee_remark: '',
    consignee_status: '',
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
      url: 'https://www.taoxiaobei.cn/wx/personal/getConsigneeInfo',
      method: 'POST',
      data: {
        thr_session: app.globalData.userSessionKey
      },
      success: function(res){
        // console.log(res.data.data)
        // 获取收货人信息，并赋给本地
        that.setData({
          consignee_name: res.data.data.consignee_name,
          consignee_phone: res.data.data.consignee_phone,
          consignee_address: res.data.data.consignee_address,
          consignee_remark: res.data.data.consignee_remark,
        })
        wx.hideLoading()
      }
    })
  },

  // 获取输入框内容
  getName: function(e){
    this.setData({
      consignee_name: e.detail.value
    })
  },
  getPhone: function(e){
    this.setData({
      consignee_phone: e.detail.value
    })
  },
  getAddress: function(e){
    this.setData({
      consignee_address: e.detail.value
    })
  },
  getRemark: function(e){
    this.setData({
      consignee_remark: e.detail.value
    })
  },

  // 修改收货人信息
  updateConsignee: function(){
    var that = this
    var app = getApp()

    if(this.data.consignee_name == null || this.data.consignee_name == "") {
      wx.showToast({
        title: "请输入收货人信息",
        icon: 'none'
      })
      return
    }

    if(this.data.consignee_phone == null || this.data.consignee_phone == "") {
      wx.showToast({
        title: "请输入收货人电话",
        icon: 'none'
      })   
      return 
    }

    if(this.data.consignee_address == null || this.data.consignee_address == "") {
      wx.showToast({
        title: "请输入收货地址",
        icon: 'none'
      })
      return
    }

    if(this.data.consignee_remark == null) {
      this.setData({
        consignee_remark: ""
      })
    }

    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/personal/updateConsigneeInfo',
      data: {
        consignee_address: that.data.consignee_address,
        consignee_name: that.data.consignee_name,
        consignee_phone: that.data.consignee_phone,
        consignee_remark: that.data.consignee_remark,
        consignee_status: 1,
        thr_session: app.globalData.userSessionKey,
      },
      method: 'POST',
      success: function(res){
        // console.log("111111111")
        // console.log(res.data)
        if(res.data.code == 200){
          wx.showToast({
            title: res.data.msg,
            icon: 'success'
          })
          // 返回上一页
          wx.navigateBack({
            delta: 1
          })
        }else{
          wx.showToast({
            title: res.data.msg,
            icon: 'none'
          })
          wx.navigateTo({
            url: '/pages/me/index',
          })
        }
      }
    })
  }
})