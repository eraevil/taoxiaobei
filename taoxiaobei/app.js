//app.js
App({
  onLaunch: function () {
    // 展示本地存储能力
    // var logs = wx.getStorageSync('logs') || []
    // logs.unshift(Date.now())
    // wx.setStorageSync('logs', logs)
    
    // 检查登录 
    // if(this.globalData.userSessionKey == null){
    //   wx.navigateTo({
    //     url: '/pages/home/login/login'
    //   })
    // }

    // wx.loadFontFace({
    //   family: 'Microsoft YaHei UI',
    //   source: 'url("https://www.taoxiaobei.cn/font/MicrosoftYaHeiUILight.ttf")',
    //   fail:function(){
    //     console.log("加载字体失败")
    //   }
    // })

    // 登录
    /*wx.login({
      success: res => {
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
        wx.request({
          url: 'https://www.taoxiaobei.cn/wx/Login/login',
          data: {
            code: res.code
          },
          method: 'POST',
          header: {
            'content-type': 'application/json'
          },
          success: function (res) {
            
          }
        })

      }
    })*/
    
    // 获取用户信息
    // wx.getSetting({
    //   success: res => {
    //     if (res.authSetting['scope.userInfo']) {
    //       // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
    //       wx.getUserInfo({
    //         success: res => {
    //           // 可以将 res 发送给后台解码出 unionId
    //           this.globalData.userInfo = res.userInfo

    //           // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
    //           // 所以此处加入 callback 以防止这种情况
    //           if (this.userInfoReadyCallback) {
    //             this.userInfoReadyCallback(res)
    //           }
    //         }
    //       })
    //     }
    //   }
    // })
  },
  globalData: {
    userSessionKey: '75338d9fd5b344186f04caaf992bd292'
  },

  // 睡眠函数
  sleep: function (numberMillis) {
    var now = new Date();
    var exitTime = now.getTime() + numberMillis;
    while (true) {
        now = new Date();
        if (now.getTime() > exitTime)
        return;
    }
  }
  
})