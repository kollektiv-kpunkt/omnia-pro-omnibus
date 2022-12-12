import pandas as pd

df = pd.read_json (r'kandis.json')

df.to_csv (r'ksndis.csv', index = None)