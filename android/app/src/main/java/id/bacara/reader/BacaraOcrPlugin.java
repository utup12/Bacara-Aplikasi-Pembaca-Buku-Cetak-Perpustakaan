package id.bacara.reader;

import android.Manifest;
import android.app.Activity;
import android.content.Intent;

import androidx.activity.result.ActivityResult;

import com.getcapacitor.ActivityCallback;
import com.getcapacitor.JSObject;
import com.getcapacitor.PermissionState;
import com.getcapacitor.Plugin;
import com.getcapacitor.PluginCall;
import com.getcapacitor.PluginMethod;
import com.getcapacitor.annotation.CapacitorPlugin;
import com.getcapacitor.annotation.Permission;
import com.getcapacitor.annotation.PermissionCallback;

@CapacitorPlugin(
    name = "BacaraOcr",
    permissions = {
        @Permission(alias = "camera", strings = { Manifest.permission.CAMERA })
    }
)
public class BacaraOcrPlugin extends Plugin {
    @PluginMethod
    public void scanDocument(PluginCall call) {
        if (getPermissionState("camera") != PermissionState.GRANTED) {
            requestPermissionForAlias("camera", call, "cameraPermissionCallback");
            return;
        }

        openScanner(call);
    }

    @PermissionCallback
    private void cameraPermissionCallback(PluginCall call) {
        if (getPermissionState("camera") != PermissionState.GRANTED) {
            call.reject("Izin kamera diperlukan untuk memindai halaman.");
            return;
        }

        openScanner(call);
    }

    private void openScanner(PluginCall call) {
        Intent intent = new Intent(getActivity(), DocumentScannerActivity.class);
        startActivityForResult(call, intent, "handleScannerResult");
    }

    @ActivityCallback
    private void handleScannerResult(PluginCall call, ActivityResult result) {
        if (call == null) return;

        if (result.getResultCode() != Activity.RESULT_OK || result.getData() == null) {
            call.reject("Pemindaian dibatalkan.");
            return;
        }

        String text = result.getData().getStringExtra(DocumentScannerActivity.RESULT_TEXT);
        if (text == null || text.trim().isEmpty()) {
            call.reject("Tidak ada teks yang terbaca.");
            return;
        }

        JSObject response = new JSObject();
        response.put("text", text);
        call.resolve(response);
    }
}
