from data_pipeline import run_pipeline

result = run_pipeline()

print("Done!" if result else "Some Big Error!")
