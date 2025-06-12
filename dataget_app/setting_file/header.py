import os
import time
import random
from setting_file import api_json 
from google.oauth2 import service_account
from google.analytics.data_v1beta import BetaAnalyticsDataClient
from google.analytics.data_v1beta.types import (
    DateRange, Metric, Dimension, RunReportRequest,
    FilterExpression, FilterExpressionList, Filter
)
from setting_file.GA4_Set.QshURL_MK_RS_UV import URLS
os.chdir(os.path.dirname(os.path.abspath(__file__)))# スクリプトが存在するディレクトリを作業ディレクトリとして設定

