package id.bacara.reader;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.media.Image;
import android.os.Bundle;
import android.view.MotionEvent;
import android.view.Surface;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.camera.core.Camera;
import androidx.camera.core.CameraSelector;
import androidx.camera.core.FocusMeteringAction;
import androidx.camera.core.ImageCapture;
import androidx.camera.core.ImageCaptureException;
import androidx.camera.core.ImageProxy;
import androidx.camera.core.MeteringPoint;
import androidx.camera.core.Preview;
import androidx.camera.lifecycle.ProcessCameraProvider;
import androidx.camera.view.PreviewView;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import com.google.common.util.concurrent.ListenableFuture;
import com.google.mlkit.vision.common.InputImage;
import com.google.mlkit.vision.text.TextRecognition;
import com.google.mlkit.vision.text.TextRecognizer;
import com.google.mlkit.vision.text.latin.TextRecognizerOptions;

import java.util.concurrent.TimeUnit;

public class DocumentScannerActivity extends AppCompatActivity {
    public static final String RESULT_TEXT = "result_text";
    private static final int CAMERA_PERMISSION_REQUEST = 401;

    private PreviewView previewView;
    private TextView statusView;
    private Button captureButton;
    private ProgressBar progressBar;
    private ProcessCameraProvider cameraProvider;
    private Camera camera;
    private ImageCapture imageCapture;
    private TextRecognizer textRecognizer;
    private boolean capturing = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_document_scanner);

        previewView = findViewById(R.id.document_preview);
        statusView = findViewById(R.id.document_scan_status);
        captureButton = findViewById(R.id.document_capture_button);
        progressBar = findViewById(R.id.document_scan_progress);
        Button cancelButton = findViewById(R.id.document_cancel_button);
        textRecognizer = TextRecognition.getClient(TextRecognizerOptions.DEFAULT_OPTIONS);

        captureButton.setOnClickListener(view -> captureAndRecognize());
        cancelButton.setOnClickListener(view -> cancelScan());
        previewView.setOnTouchListener(this::focusCameraAtTap);

        if (hasCameraPermission()) {
            startCamera();
        } else {
            ActivityCompat.requestPermissions(
                this,
                new String[] { Manifest.permission.CAMERA },
                CAMERA_PERMISSION_REQUEST
            );
        }
    }

    private boolean hasCameraPermission() {
        return ContextCompat.checkSelfPermission(this, Manifest.permission.CAMERA)
            == PackageManager.PERMISSION_GRANTED;
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == CAMERA_PERMISSION_REQUEST && hasCameraPermission()) {
            startCamera();
        } else if (requestCode == CAMERA_PERMISSION_REQUEST) {
            statusView.setText("Izin kamera diperlukan untuk memindai halaman.");
            captureButton.setEnabled(false);
        }
    }

    private void startCamera() {
        statusView.setText("Arahkan kamera lurus ke satu halaman. Ketuk tulisan untuk memfokuskan.");
        ListenableFuture<ProcessCameraProvider> providerFuture = ProcessCameraProvider.getInstance(this);
        providerFuture.addListener(() -> {
            try {
                cameraProvider = providerFuture.get();
                Preview preview = new Preview.Builder().build();
                imageCapture = new ImageCapture.Builder()
                    .setCaptureMode(ImageCapture.CAPTURE_MODE_MAXIMIZE_QUALITY)
                    .setTargetRotation(Surface.ROTATION_0)
                    .build();

                cameraProvider.unbindAll();
                camera = cameraProvider.bindToLifecycle(
                    this,
                    CameraSelector.DEFAULT_BACK_CAMERA,
                    preview,
                    imageCapture
                );
                preview.setSurfaceProvider(previewView.getSurfaceProvider());
                captureButton.setEnabled(true);
            } catch (Exception error) {
                statusView.setText("Kamera tidak dapat dibuka. Tutup aplikasi lain yang memakai kamera lalu coba lagi.");
                captureButton.setEnabled(false);
            }
        }, ContextCompat.getMainExecutor(this));
    }

    private boolean focusCameraAtTap(View view, MotionEvent event) {
        if (event.getAction() != MotionEvent.ACTION_UP || camera == null) return true;

        MeteringPoint point = previewView.getMeteringPointFactory().createPoint(event.getX(), event.getY());
        FocusMeteringAction action = new FocusMeteringAction.Builder(
            point,
            FocusMeteringAction.FLAG_AF | FocusMeteringAction.FLAG_AE
        ).setAutoCancelDuration(3, TimeUnit.SECONDS).build();
        camera.getCameraControl().startFocusAndMetering(action);
        statusView.setText("Memfokuskan kamera. Tunggu sebentar lalu ambil foto.");
        return true;
    }

    private void captureAndRecognize() {
        if (imageCapture == null || capturing) return;

        capturing = true;
        captureButton.setEnabled(false);
        progressBar.setVisibility(View.VISIBLE);
        statusView.setText("Mengambil foto resolusi penuh dan membaca teks…");

        imageCapture.takePicture(
            ContextCompat.getMainExecutor(this),
            new ImageCapture.OnImageCapturedCallback() {
                @Override
                public void onCaptureSuccess(@NonNull ImageProxy imageProxy) {
                    Image image = imageProxy.getImage();
                    if (image == null) {
                        imageProxy.close();
                        showCaptureError("Foto kamera tidak tersedia. Coba lagi.");
                        return;
                    }

                    InputImage inputImage = InputImage.fromMediaImage(
                        image,
                        imageProxy.getImageInfo().getRotationDegrees()
                    );
                    textRecognizer.process(inputImage)
                        .addOnSuccessListener(result -> finishWithText(result.getText()))
                        .addOnFailureListener(error -> showCaptureError("Teks tidak dapat dibaca. Bersihkan lensa dan coba lagi."))
                        .addOnCompleteListener(task -> imageProxy.close());
                }

                @Override
                public void onError(@NonNull ImageCaptureException exception) {
                    showCaptureError("Foto tidak dapat diambil. Coba lagi.");
                }
            }
        );
    }

    private void finishWithText(String text) {
        if (text == null || text.trim().isEmpty()) {
            showCaptureError("Belum ada teks yang terbaca. Dekatkan kamera dan pastikan halaman terang.");
            return;
        }

        Intent result = new Intent();
        result.putExtra(RESULT_TEXT, text);
        setResult(RESULT_OK, result);
        finish();
    }

    private void showCaptureError(String message) {
        capturing = false;
        progressBar.setVisibility(View.GONE);
        captureButton.setEnabled(true);
        statusView.setText(message);
    }

    private void cancelScan() {
        setResult(RESULT_CANCELED);
        finish();
    }

    @Override
    protected void onDestroy() {
        if (cameraProvider != null) cameraProvider.unbindAll();
        if (textRecognizer != null) textRecognizer.close();
        super.onDestroy();
    }
}
