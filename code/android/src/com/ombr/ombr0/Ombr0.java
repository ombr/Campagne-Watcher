package com.ombr.ombr0;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.widget.TextView;
import android.widget.Toast;

public class Ombr0 extends Activity {
	/** Called when the activity is first created. */
	int a =0;
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		TextView tv = new TextView(this);
		tv.setText("Cette application se lance automatiquement lors de la reception de texto !");
		setContentView(tv);
		/*while(true){
			try {
				URL url = new URL(
				"http://luc.boissaye.fr/campagnewatcher/send.php");
				DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
				DocumentBuilder db = dbf.newDocumentBuilder();
				Document doc = db.parse(new InputSource(url.openStream()));
				doc.getDocumentElement().normalize();
				NodeList nodeList = doc.getElementsByTagName("item");
				for (int i = 0; i < nodeList.getLength(); i++) {
					Node node = nodeList.item(i);
					log(node.getAttributes().getNamedItem("num").getNodeValue()+">"+node.getFirstChild().getNodeValue());
					SmsManager.getDefault().sendTextMessage(node.getAttributes().getNamedItem("num").getNodeValue(), null, node.getFirstChild().getNodeValue(), null, null);
				}
			}catch (Exception e) {
				log("fini : "+e.getMessage());
				break;
			}
			a++;
			if(a>20){
				log("STOP SECURITE !");
				break;
			}
		}*/
	}
	public void  startActivity(Intent intent){
		TextView tv = new TextView(this);
		setContentView(tv);
	}
	public void log(String s){
		Toast toast = Toast.makeText(getApplicationContext(), s, Toast.LENGTH_LONG);
		toast.show(); 
	}

}

