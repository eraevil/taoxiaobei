Page({
  data: {
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    encryptedData: null,
    userInfo: ''
  },
  onLoad: function() {
    
  },

  bindGetUserInfo: function(e) {
    let self = this
    wx.getUserProfile({
      desc: "获取你的昵称、头像、地区及性别", // 不写不弹提示框
      success: res => {
        // 授权成功
        // console.log(res)
        self.setData({
          userInfo: res.userInfo,
          encryptedData: res.encryptedData,
          iv: res.iv
        })

        // 用户登录
        wx.login({
          success (res) {
            if (res.code) {
              // 成功拿到code
              // 发起网络请求
            wx.request({
                url: 'https://www.taoxiaobei.cn/wx/login/login',
                header: {
                  "content-type": "application/x-www-form-urlencoded"
                },
                method:'POST',
                data: {
                  encryptedData: self.data.encryptedData,
                  iv: self.data.iv,
                  code: res.code,
                  user_headimg: self.data.userInfo.avatarUrl,
                  nick_name: self.data.userInfo.nickName,
                  user_sex: self.data.userInfo.gender,
                },
                success:function(res){
                  wx.switchTab({
                    url: '/pages/home/index'
                  })
                  // console.log("注册成功")

                  // console.log(res.data.data)
                  //console.log(res.data.data)
                  const app = getApp()
                  app.globalData.userSessionKey = res.data.data
                },
                fail:function(){
                  console.log("登录失败")
                }
              })
            }
          }
        })
      },
      fail: res => {
        //拒绝授权
        wx.showToast({
          title: '您拒绝了授权',
          icon: 'none'
        })
        return;
      }
    })    
  }
})