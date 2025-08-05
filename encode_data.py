import pandas as pd
from sklearn.preprocessing import StandardScaler, LabelEncoder

# Step 1: Load Excel file
df = pd.read_excel("Earth_Tekniks_Report.xlsx", sheet_name="Report")

# Step 2: Standardize numeric columns
cols_to_standardize = ['Moisture %', 'Min', 'Max', 'Avg', 'Temperature']
scaler = StandardScaler()
df[cols_to_standardize] = scaler.fit_transform(df[cols_to_standardize])

# Step 3: Label encode the 'Status' column
label_encoder = LabelEncoder()
df['Status_Encoded'] = label_encoder.fit_transform(df['Status'])

# Step 4: Save to new Excel file
df.to_excel("Earth_Tekniks_Report_Encoded.xlsx", index=False)

print("✅ Done! Encoded file saved as Earth_Tekniks_Report_Encoded.xlsx")
