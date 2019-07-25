@php
    $establishment = $document->establishment;
@endphp
{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!}
<Perception xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:Perception-1"
            xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
            xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
            xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
            xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
            xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
    <cbc:CustomizationID>1.0</cbc:CustomizationID>
    <cac:Signature>
        <cbc:ID>{{ config('configuration.signature_uri') }}</cbc:ID>
        <cbc:Note>{{ config('configuration.signature_note') }}</cbc:Note>
        <cac:SignatoryParty>
            <cac:PartyIdentification>
                <cbc:ID>{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
            </cac:PartyName>
        </cac:SignatoryParty>
        <cac:DigitalSignatureAttachment>
            <cac:ExternalReference>
                <cbc:URI>#{{ config('configuration.signature_uri') }}</cbc:URI>
            </cac:ExternalReference>
        </cac:DigitalSignatureAttachment>
    </cac:Signature>
    <cbc:ID>{{ $document->series }}-{{ $document->number }}</cbc:ID>
    <cbc:IssueDate>{{ $document->date_of_issue }}</cbc:IssueDate>
    <cbc:IssueTime>{{ $document->time_of_issue }}</cbc:IssueTime>
    <cac:AgentParty>
        <cac:PartyIdentification>
            <cbc:ID schemeID="6">{{ $company->number }}</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyName>
            <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
        </cac:PartyName>
        <cac:PostalAddress>
            <cbc:ID>{{ $establishment->district_id }}</cbc:ID>
            <cbc:StreetName><![CDATA[{{ $establishment->address }}]]></cbc:StreetName>
            <cbc:CityName>{{ $establishment->department->description }}</cbc:CityName>
            <cbc:CountrySubentity>{{ $establishment->province->description }}</cbc:CountrySubentity>
            <cbc:District>{{ $establishment->district->description }}</cbc:District>
            <cac:Country>
                <cbc:IdentificationCode>{{ $establishment->country_id }}</cbc:IdentificationCode>
            </cac:Country>
        </cac:PostalAddress>
        <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>
        </cac:PartyLegalEntity>
    </cac:AgentParty>
    <cac:ReceiverParty>
        <cac:PartyIdentification>
            <cbc:ID schemeID="{{ $document->supplier->document_type_id }}">{{ $document->supplier->number }}</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA[{{ $document->supplier->name }}]]></cbc:RegistrationName>
        </cac:PartyLegalEntity>
    </cac:ReceiverParty>
    <sac:SUNATPerceptionSystemCode>{{ $document->perception_type_id }}</sac:SUNATPerceptionSystemCode>
    <sac:SUNATPerceptionPercent>{{ $document->percentage }}</sac:SUNATPerceptionPercent>
    @if($document->observation)
    <cbc:Note><![CDATA[{{ $document->observation }}]]></cbc:Note>
    @endif
    <cbc:TotalInvoiceAmount currencyID="PEN">{{ $document->total }}</cbc:TotalInvoiceAmount>
    <sac:SUNATTotalCashed currencyID="PEN">{{ $document->total_payment }}</sac:SUNATTotalCashed>
    @foreach($document->details as $detail)
    @php($doc = $detail->document)
    <sac:SUNATPerceptionDocumentReference>
        <cbc:ID schemeID="{{ $doc->document_type_id }}">{{ $doc->number_full }}</cbc:ID>
        <cbc:IssueDate>{{ $doc->date_of_issue->format('Y-m-d') }}</cbc:IssueDate>
        <cbc:TotalInvoiceAmount currencyID="{{ $doc->currency_type_id }}">{{ $doc->total }}</cbc:TotalInvoiceAmount>
        @if($doc->payments)
        @foreach($doc->payments as $payment)
        <cac:Payment>
            <cbc:ID>{{ $loop->iteration }}</cbc:ID>
            <cbc:PaidAmount currencyID="{{ $payment->currency_type_id }}">{{ $payment->total }}</cbc:PaidAmount>
            <cbc:PaidDate>{{ $payment->date_of_issue->format('Y-m-d') }}</cbc:PaidDate>
        </cac:Payment>
        @endforeach
        @endif
        @if($doc->amount && $doc->payment && $doc->date_of_perception)
        <sac:SUNATPerceptionInformation>
            <sac:SUNATPerceptionAmount currencyID="PEN">{{ $doc->amount  }}</sac:SUNATPerceptionAmount>
            <sac:SUNATPerceptionDate>{{ $doc->date_of_perception->format('Y-m-d') }}</sac:SUNATPerceptionDate>
            <sac:SUNATNetTotalCashed currencyID="PEN">{{ $doc->payment }}</sac:SUNATNetTotalCashed>
            @if($doc->exchange_rate)
            <cac:ExchangeRate>
                <cbc:SourceCurrencyCode>{{ $doc->exchange_rate->source_currency_type_id }}</cbc:SourceCurrencyCode>
                <cbc:TargetCurrencyCode>{{ $doc->exchange_rate->target_currency_type_id }}</cbc:TargetCurrencyCode>
                <cbc:CalculationRate>{{ $doc->exchange_rate->rate }}</cbc:CalculationRate>
                <cbc:Date>{{ $doc->exchange_rate->date->format('Y-m-d') }}</cbc:Date>
            </cac:ExchangeRate>
            @endif
        </sac:SUNATPerceptionInformation>
        @endif
    </sac:SUNATPerceptionDocumentReference>
    @endforeach
</Perception>