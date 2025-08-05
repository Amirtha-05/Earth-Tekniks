import pandas as pd
import numpy as np
import os
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Conv1D, MaxPooling1D, Flatten, Dense, Dropout

# ✅ Force working directory to script's folder
os.chdir(os.path.dirname(os.path.abspath(__file__)))
print("✅ Script started...")
print("📁 Working directory:", os.getcwd())

# Step 1: Load Excel file
input_file = "Earth_Tekniks_Report_Final_Encoded.xlsx"
if not os.path.exists(input_file):
    print(f"❌ File not found: {input_file}")
    exit()

df = pd.read_excel(input_file)
print(f"📄 Loaded data from {input_file} — shape: {df.shape}")

# Step 2: Clean and split features (X) and target (y)
# 👇 This converts all feature columns to float and handles bad data
X = df.iloc[:, :-1].apply(pd.to_numeric, errors='coerce').fillna(0).astype(np.float32).values
y = df.iloc[:, -1].values

# Step 3: Label encode y if needed
if y.dtype == object or not np.issubdtype(y.dtype, np.integer):
    le = LabelEncoder()
    y = le.fit_transform(y)
    print("🔤 Labels encoded.")
else:
    print("🔢 Labels already numeric.")

# Step 4: Reshape X for CNN input
X = X.reshape((X.shape[0], X.shape[1], 1))
print(f"🔁 Reshaped X to: {X.shape}")

# Step 5: Train/test split
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)
print("🧪 Train/test split complete.")

# Step 6: Build CNN model
model = Sequential([
    Conv1D(64, kernel_size=2, activation='relu', input_shape=(X.shape[1], 1)),
    MaxPooling1D(pool_size=2),
    Flatten(),
    Dense(100, activation='relu'),
    Dropout(0.3),
    Dense(len(np.unique(y)), activation='softmax')  # For classification
])
model.compile(optimizer='adam', loss='sparse_categorical_crossentropy', metrics=['accuracy'])
print("🛠️ Model compiled.")

# Step 7: Train
print("🚀 Training model...")
model.fit(X_train, y_train, epochs=20, batch_size=16, validation_data=(X_test, y_test))
print("✅ Model training complete.")

# Step 8: Predict
print("🔮 Predicting...")
predictions = model.predict(X_test)
predicted_classes = predictions.argmax(axis=1)

# Step 9: Show first 10 results
print("\n📊 Predicted vs Actual (first 10 samples):")
for i in range(min(10, len(y_test))):
    print(f"Actual: {y_test[i]} → Predicted: {predicted_classes[i]}")

# Step 10: Save to Excel
results_df = pd.DataFrame({
    "Actual": y_test,
    "Predicted": predicted_classes
})
output_file = "CNN_Predictions.xlsx"
results_df.to_excel(output_file, index=False)
print(f"\n✅ Predictions saved to {output_file}")
